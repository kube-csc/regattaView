<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Tabele;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EventSelectionService
{
    /**
     * Liefert die passende Event-Group fuer die aktuelle Domain.
     * Wird fuer Headerbild/Titel verwendet und gecacht.
     */
    public function getCurrentEventGroupHeader(): ?object
    {
        $baseDomain = $this->getCurrentBaseDomain();

        $cacheKey = sprintf(
            'event_groups:header:visible:domain_%s',
            $baseDomain !== '' ? $baseDomain : 'none'
        );

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($baseDomain) {
            $eventGroups = DB::table('event_groups')
                ->where('visible', 1)
                ->orderByDesc('id')
                ->get();

            if ($baseDomain === '') {
                return $eventGroups->first();
            }

            return $eventGroups->first(function ($eventGroup) use ($baseDomain) {
                $groupDomain = (string) ($eventGroup->liveDomain ?? '');

                return $this->normalizeDomain($groupDomain) === $baseDomain;
            });
        });
    }

    /**
     * Extrahiert die Basis-Domain ohne Subdomain aus einem Hostnamen.
     */
    private function getBaseDomain(string $host): string
    {
        $host = strtolower(trim($host));
        $host = preg_replace('/:\\d+$/', '', $host) ?? $host;
        $host = preg_replace('/^www\\./', '', $host) ?? $host;

        if ($host === '' || $host === 'localhost' || filter_var($host, FILTER_VALIDATE_IP)) {
            return $host;
        }

        $parts = explode('.', $host);
        if (count($parts) <= 2) {
            return $host;
        }

        return implode('.', array_slice($parts, -2));
    }

    /**
     * Normalisiert Domain-Eingaben (mit/ohne Schema/Pfad) auf Basis-Domain.
     */
    private function normalizeDomain(string $domain): string
    {
        $domain = trim(strtolower($domain));
        if ($domain === '') {
            return '';
        }

        if (!str_starts_with($domain, 'http://') && !str_starts_with($domain, 'https://')) {
            $domain = 'https://' . $domain;
        }

        $host = parse_url($domain, PHP_URL_HOST);
        if (!is_string($host) || $host === '') {
            return '';
        }

        return $this->getBaseDomain($host);
    }

    /**
     * Ermittelt die aktuelle Basis-Domain aus Request oder APP_URL.
     */
    private function getCurrentBaseDomain(): string
    {
        $host = request()->getHost();
        if (!is_string($host) || $host === '') {
            $host = (string) parse_url((string) config('app.url'), PHP_URL_HOST);
        }

        return $this->getBaseDomain($host);
    }


    /**
     * Liefert das naechste Regatta-Event mit Anmeldetext.
     * Zeigt das Event solange an, bis das naechste Event in 14 Tagen ansteht.
     */
    public function getNextRegattaEventWithAnmeldetext(int $daysBefore = 14): ?Event
    {
        $today = Carbon::today()->toDateString();
        $dateBefore = Carbon::today()->addDays($daysBefore)->toDateString();
        $baseDomain = $this->getCurrentBaseDomain();

        $cacheKey = sprintf(
            'events:current_anmeldetext:verwendung0:days_%d:today_%s:domain_%s',
            $daysBefore,
            $today,
            $baseDomain !== '' ? $baseDomain : 'none'
        );

        // Kurzer Cache reduziert DB-Last, reagiert aber zeitnah auf Aenderungen.
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($dateBefore, $today, $daysBefore, $baseDomain) {
            $baseQuery = Event::query()
                ->join('event_groups', 'events.eventGroup_id', '=', 'event_groups.id')
                ->where('events.regatta', 1)
                ->where('events.verwendung', 0)
                ->where('event_groups.visible', 1)
                ->whereNotNull('event_groups.liveDomain')
                ->whereRaw("TRIM(event_groups.liveDomain) <> ''")
                ->whereNotNull('events.anmeldetext')
                ->whereRaw("TRIM(events.anmeldetext) <> ''")
                ->select('events.*', 'event_groups.liveDomain as event_group_domain');

            // Wenn baseDomain gesetzt ist, filtere nur Events dieser Domain
            if ($baseDomain !== '') {
                $baseQuery->where('event_groups.liveDomain', 'LIKE', '%' . $baseDomain . '%');
            }

            $query = clone $baseQuery;
            $query->whereDate('events.datumvon', '<=', $dateBefore)  // nur Events die jetzt laufen oder in $daysBefore Tage starten
                ->whereDate('events.datumbis', '>=', $today)          // keine Events die bereits abgelaufen sind
                ->orderBy('events.datumvon');

            $allEvents = $query->get();

            if ($allEvents->isEmpty()) {
                // Kein aktuelles oder zukünftiges Event gefunden – letztes abgelaufenes Event anzeigen
                $lastExpiredQuery = clone $baseQuery;
                $lastExpiredQuery = $lastExpiredQuery
                    ->whereDate('events.datumbis', '<', $today)
                    ->orderByDesc('events.datumbis')
                    ->orderByDesc('events.datumvon');

                $lastExpired = $lastExpiredQuery->first();

                return $lastExpired ?? null;
            }

            // Laufende Events haben die hoechste Prioritaet.
            $runningEvent = $allEvents
                ->where('datumvon', '<=', $today)
                ->where('datumbis', '>=', $today)
                ->last();

            if ($runningEvent) {
                return $runningEvent;
            }

            // Sonst: letztes bereits gestartetes Event.
            $currentEvent = $allEvents->where('datumvon', '<=', $today)->last();

            // Falls es noch kein gestartetes Event gibt, nimm das erste kommende.
            if (!$currentEvent) {
                $currentEvent = $allEvents->first();
            }

            if (!$currentEvent) {
                return null;
            }

            // Wenn das naechste Event 14 Tage vorher ansteht, auf dieses umschalten.
            $nextEvent = $allEvents->where('datumvon', '>', $currentEvent->datumvon)->first();
            if ($nextEvent) {
                $switchDate = Carbon::createFromFormat('Y-m-d', $nextEvent->datumvon)
                    ->subDays($daysBefore)
                    ->toDateString();

                if ($today >= $switchDate) {
                    return $nextEvent;
                }
            }

            return $currentEvent;
        });
    }

    /**
     * Prueft gecacht, ob das aktuelle Event mindestens eine sichtbare Tabelle hat.
     */
    public function currentEventHasVisibleTables(int $daysBefore = 14): bool
    {
        $event = $this->getNextRegattaEventWithAnmeldetext($daysBefore);

        if (!$event) {
            return false;
        }

        $cacheKey = sprintf('events:%d:has_visible_tables', $event->id);

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($event) {
            return Tabele::where('event_id', $event->id)
                ->where('tabelleVisible', 1)
                ->exists();
        });
    }
}

