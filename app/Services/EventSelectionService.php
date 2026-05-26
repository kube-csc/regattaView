<?php

namespace App\Services;

use App\Models\Event;
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
     * Beruecksichtigt nur Events (verwendung=0), die aktuell laufen
     * oder innerhalb des Vorlauf-Fensters starten.
     */
    public function getNextRegattaEventWithAnmeldetext(int $daysBefore = 14): ?Event
    {
        $today = Carbon::today()->toDateString();
        $showFromDate = Carbon::today()->addDays($daysBefore)->toDateString();
        $baseDomain = $this->getCurrentBaseDomain();

        $cacheKey = sprintf(
            'events:next_regatta:anmeldetext:verwendung0:days_%d:today_%s:domain_%s',
            $daysBefore,
            $today,
            $baseDomain !== '' ? $baseDomain : 'none'
        );

        // Kurzer Cache reduziert DB-Last, reagiert aber zeitnah auf Aenderungen.
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($today, $showFromDate, $baseDomain) {
            $events = Event::query()
                ->join('event_groups', 'events.eventGroup_id', '=', 'event_groups.id')
                ->where('events.regatta', 1)
                ->where('events.verwendung', 0)
                ->where('event_groups.visible', 1)
                ->whereNotNull('event_groups.liveDomain')
                ->whereRaw("TRIM(event_groups.liveDomain) <> ''")
                ->whereNotNull('events.anmeldetext')
                ->whereRaw("TRIM(events.anmeldetext) <> ''")
                ->whereDate('events.datumbis', '>=', $today)
                ->whereDate('events.datumvon', '<=', $showFromDate)
                ->select('events.*', 'event_groups.liveDomain as event_group_domain')
                ->orderBy('events.datumvon')
                ->get();

            if ($baseDomain === '') {
                return $events->first();
            }

            return $events->first(function (Event $event) use ($baseDomain) {
                return $this->normalizeDomain((string) $event->event_group_domain) === $baseDomain;
            });
        });
    }
}

