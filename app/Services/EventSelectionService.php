<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class EventSelectionService
{
    /**
     * Liefert das aktuelle Regatta-Event (verwendung=0).
     */
    public function getCurrentRegattaEvent(): ?Event
    {
        $cacheKey = 'events:current_regatta:verwendung0';

        return Cache::remember($cacheKey, now()->addMinutes(10), function () {
            return Event::query()
                ->where('events.regatta', 1)
                ->where('events.verwendung', 0)
                ->orderByDesc('events.datumvon')
                ->first();
        });
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

        $cacheKey = sprintf(
            'events:next_regatta:anmeldetext:verwendung0:days_%d:today_%s',
            $daysBefore,
            $today
        );

        // Kurzer Cache reduziert DB-Last, reagiert aber zeitnah auf Aenderungen.
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($today, $showFromDate) {
            return Event::query()
                ->where('events.regatta', 1)
                ->where('events.verwendung', 0)
                ->whereNotNull('events.anmeldetext')
                ->whereRaw("TRIM(events.anmeldetext) <> ''")
                ->whereDate('events.datumbis', '>=', $today)
                ->whereDate('events.datumvon', '<=', $showFromDate)
                ->orderBy('events.datumvon')
                ->first();
        });
    }
}

