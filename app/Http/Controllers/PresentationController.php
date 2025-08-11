<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\RegattaTeam;
use App\Models\Race;
use App\Models\Tabele;
use App\Models\RegattaInformation;
use Illuminate\Http\Request;

class PresentationController extends Controller
{
    // Gibt ein Array mit ['eventId' => ..., 'event' => ...] zurück
    private function getCurrentEvent()
    {
        $event = Event::join('races as ra' , 'events.id' , '=' , 'ra.event_id')
            ->where('events.regatta' , '1')
            ->where('events.verwendung' , 0)
            ->orderby('events.datumvon' , 'desc')
            ->first();

        return $event;
    }

    public function welcome()
    {
        $event= $this->getCurrentEvent();

        return view('presentation.welcome', compact('event'));
    }

    public function teams(Request $request)
    {
        $event = $this->getCurrentEvent();
        $eventId = $event->event_id;

        // Teams nach Wertungsgruppen sortieren
        $teams = RegattaTeam::where('regatta_id', $eventId)
            ->where('status', '!=','Gelöscht')
            ->orderBy('gruppe_id')
            ->orderBy('teamname')
            ->limit(10)  // Temp: Testweise nur 10 Teams laden
            ->get();

        $wertungsGruppen = $teams->groupBy(function ($team) {
            return $team->teamWertungsGruppe ? $team->teamWertungsGruppe->typ : 'ohne Wertung';
        });

        $groupKeys = $wertungsGruppen->keys()->values();
        $groupIndex = (int) $request->query('group', 0);

        // Wenn keine Gruppen vorhanden sind, gib leere Werte zurück
        if ($groupKeys->isEmpty()) {
            return view('presentation.teams', [
                'wertung' => '',
                'teams' => collect(),
                'nextUrl' => route('presentation.laneOccupancy'),
                'event' => $event,
                'nextGroupIndex' => 0,
            ]);
        }

        $teamsInGroup = $wertungsGruppen->get($groupKeys[$groupIndex]) ?? collect();
        $page = (int) $request->query('page', 1);

        // Pagination für Teams innerhalb einer Gruppe (max 15 pro Seite)
        $teamsPerPage = 15;
        $totalPages = max(1, ceil($teamsInGroup->count() / $teamsPerPage));
        $page = max(1, min($page, $totalPages));
        $start = ($page - 1) * $teamsPerPage;
        $pagedTeams = $teamsInGroup->slice($start, $teamsPerPage);

        // Nächste Seite/Gruppe bestimmen
        if ($page < $totalPages) {
            $nextGroupIndex = $groupIndex;
            $nextPage = $page + 1;
        } else {
            $nextGroupIndex = $groupIndex + 1;
            $nextPage = 1;
        }

        // Wenn alle Gruppen durch sind, weiter zum Mannschaftssteckbrief
        if ($nextGroupIndex >= count($groupKeys)) {
            $nextUrl = route('presentation.teamProfile');
        } else {
            $nextUrl = route('presentation.teams', ['group' => $nextGroupIndex, 'page' => $nextPage]);
        }

        // Gruppenzählung für die Fußnote
        $currentGroupNumber = $groupIndex + 1;
        $totalGroups = count($groupKeys);

        return view('presentation.teams', [
            'wertung' => $groupKeys[$groupIndex] ?? '',
            'teams' => $pagedTeams,
            'nextUrl' => $nextUrl,
            'event' => $event,
            'nextGroupIndex' => $nextGroupIndex,
            'currentGroupNumber' => $currentGroupNumber,
            'totalGroups' => $totalGroups,
            'count' => $teamsInGroup->count(),
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    public function laneOccupancy()
    {
        $event = $this->getCurrentEvent();
        $eventId = $event->event_id;

        // Nur verloste und sichtbare Rennen (status == 2, visible == 1)
        $races = Race::with(['lanes.regattaTeam'])
            ->where('event_id', $eventId)
            ->where('status', 2)
            ->where('visible', 1)
            ->orderBy('rennDatum')
            ->orderBy('rennZeit')
            ->get();

        // Wenn keine Rennen vorhanden sind, direkt zur nächsten Präsentationsseite (Ergebnisse)
        if ($races->isEmpty()) {
            return redirect()->route('presentation.result');
        }

        return view('presentation.laneOccupancy', [
            'races' => $races,
            'event' => $event,
        ]);
    }

    public function result()
    {
        $event = $this->getCurrentEvent();
        $eventId = $event->event_id;

        // Nur Rennen mit Ergebnis und sichtbar (status == 4, visible == 1)
        $races = Race::with(['lanes.regattaTeam'])
            ->where('event_id', $eventId)
            ->where('status', 4)
            ->where('visible', 1)
            ->orderBy('rennDatum')
            ->orderBy('rennZeit')
            ->get();

        // Wenn keine Rennen vorhanden sind, direkt zur nächsten Präsentationsseite (Video)
        if ($races->isEmpty()) {
            return redirect()->route('presentation.video');
            // return redirect()->route('presentation.welcome'); // Temp: Zurück zur Willkommensseite
        }

        return view('presentation.result', [
            'races' => $races,
            'event' => $event,
        ]);
    }

    public function video()
    {
        $event = $this->getCurrentEvent();
        return view('presentation.video', [
            'event' => $event,
        ]);
    }

    public function table()
    {
        $event = $this->getCurrentEvent();
        $eventId = $event->event_id;

        // Lade Tabellen mit Tabellendaten und Team
        $tables = Tabele::with(['tabeledataShows.getMannschaft'])
            ->where('event_id', $eventId)
            ->where('tabelleVisible', 1)
            ->orderBy('id')
            ->get()
            // Filtere Tabellen ohne Platzierungen heraus
            ->filter(function($table) {
                return $table->tabeledataShows && $table->tabeledataShows->count() > 0;
            })
            ->values();

        // Wenn keine Tabellen vorhanden sind, direkt zur nächsten Präsentationsseite (Video)
        if ($tables->isEmpty()) {
            return redirect()->route('presentation.video');
        }

        return view('presentation.table', [
            'tables' => $tables,
            'event' => $event,
        ]);
    }

    public function teamProfile(Request $request)
    {
        $event = $this->getCurrentEvent();
        $eventId = $event->event_id;

        // Prüfen, ob bereits ein Ergebnis existiert (status > 2)
        $hasResults = \App\Models\Race::where('event_id', $eventId)
            ->where('status', '>', 2)
            ->exists();

        if ($hasResults) {
            // Wenn Ergebnisse existieren, Teamprofile überspringen
            return redirect()->route('presentation.laneOccupancy');
        }

        // Alle Teams des aktuellen Events
        $teams = RegattaTeam::where('regatta_id', $eventId)
            ->where('status', '!=', 'Gelöscht')
            ->orderBy('teamname')
            ->limit(2)  // Temp: Testweise nur
            ->get();

        $teamIndex = (int) $request->query('team', 0);
        $teamCount = $teams->count();

        if ($teamCount === 0) {
            return view('presentation.teamProfile', [
                'team' => null,
                'teamIndex' => 0,
                'teamCount' => 0,
                'nextUrl' => route('presentation.laneOccupancy'),
                'event' => $event,
            ]);
        }

        $teamIndex = max(0, min($teamIndex, $teamCount - 1));
        $team = $teams[$teamIndex];

        // Nächste URL bestimmen
        $nextIndex = $teamIndex + 1;
        if ($nextIndex >= $teamCount) {
            $nextUrl = route('presentation.laneOccupancy');
        } else {
            $nextUrl = route('presentation.teamProfile', ['team' => $nextIndex]);
        }

        return view('presentation.teamProfile', [
            'team' => $team,
            'teamIndex' => $teamIndex,
            'teamCount' => $teamCount,
            'nextUrl' => $nextUrl,
            'event' => $event,
        ]);
    }

    public function information(\Illuminate\Http\Request $request)
    {
        $event = $this->getCurrentEvent();
        $eventId = $event?->event_id;

        $now = now();
        $infos = RegattaInformation::where('visible', 1)
            ->where('event_id', $eventId)
            ->where(function($q) use ($now) {
                $q->where('startDatumAktiv', 0)
                  ->orWhere(function($q2) use ($now) {
                      $q2->where('startDatumAktiv', 1)
                         ->where('startDatum', '<=', $now);
                  });
            })
            ->where(function($q) use ($now) {
                $q->where('endDatumAktiv', 0)
                  ->orWhere(function($q2) use ($now) {
                      $q2->where('endDatumAktiv', 1)
                         ->where('endDatum', '>=', $now);
                  });
            })
            ->orderBy('startDatum')
            ->get();

        $infoCount = $infos->count();
        if ($infoCount === 0) {
            return view('presentation.information', [
                'info' => null,
                'infoIndex' => 0,
                'infoCount' => 0,
                'nextUrl' => route('presentation.teams')
            ]);
        }

        $index = (int) $request->query('i', 0);
        if ($index < 0) $index = 0;
        if ($index >= $infoCount) $index = $infoCount - 1;

        $nextIndex = $index + 1;
        $nextUrl = $nextIndex < $infoCount
            ? route('presentation.information', ['i' => $nextIndex])
            : route('presentation.teams');

        return view('presentation.information', [
            'info' => $infos[$index],
            'infoIndex' => $index,
            'infoCount' => $infoCount,
            'nextUrl' => $nextUrl
        ]);
    }
}
