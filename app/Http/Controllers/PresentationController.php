<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\RegattaTeam;
use App\Models\Race;
use App\Models\Tabele;
use App\Models\RegattaInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PresentationController extends Controller
{
    protected $regattaStarted = false;
    protected $event = null;

    private function initEventAndRegattaStarted()
    {
        // Event initialisieren
        if (Session::has('event')) {
            $this->event = Session::get('event');
        } else {
            $this->event = $this->getCurrentEvent();
            if ($this->event) {
                Session::put('event', $this->event);
            }
        }

        // Regatta-Status initialisieren
        // Nur solange er nicht true ist, immer wieder prüfen
        if (Session::has('regattaStarted') && Session::get('regattaStarted') === true) {
            $this->regattaStarted = true;
        } else {
            $this->regattaStarted = $this->ermittleRegattaStarted();
            if ($this->regattaStarted) {
                Session::put('regattaStarted', true);
            } else {
                Session::put('regattaStarted', false);
            }
        }
    }

    private function ermittleRegattaStarted()
    {
        if ($this->event) {
            $eventId = $this->event->event_id;
            return Race::where('event_id', $eventId)
                ->where('status', '>', 2)
                ->exists();
        }
        return false;
    }

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

    /**
     * Prüft, ob ein neues Rennen-Ergebnis vorliegt, das noch nicht angezeigt wurde.
     * Gibt das neue Rennen zurück oder null.
     * Zusätzlich: Wenn sliteShowResult = 1, gilt das Rennen auch als $newRace.
     * Wird ein Rennen über sliteShowResult gefunden, wird das Feld zurückgesetzt.
     */
    private function checkForNewRaceResult()
    {
        if (!$this->event) return null;
        $eventId = $this->event->event_id;

        // Hole das zuletzt angezeigte Ergebnis-Datum und -Uhrzeit aus der Session
        $lastDatum = session('lastShownResultRennDatum', null);
        $lastUhrzeit = session('lastShownResultRennUhrzeit', null);

        // Finde das älteste neue Ergebnis (status==4, visible==1, nach Datum/Uhrzeit sortiert)
        $query = \App\Models\Race::where('event_id', $eventId)
            ->where('status', 4)
            ->where('visible', 1);

        if ($lastDatum !== null && $lastUhrzeit !== null) {
            $query->where(function($q) use ($lastDatum, $lastUhrzeit) {
                $q->where('rennDatum', '>', $lastDatum)
                  ->orWhere(function($q2) use ($lastDatum, $lastUhrzeit) {
                      $q2->where('rennDatum', $lastDatum)
                         ->where('rennUhrzeit', '>', $lastUhrzeit);
                  });
            });
        }

        // Prüfe zuerst auf sliteShowResult = 1
        $sliteShowRace = \App\Models\Race::where('event_id', $eventId)
            ->where('status', 4)
            ->where('visible', 1)
            ->where('sliteShowResult', 1)
            ->orderBy('rennDatum')
            ->orderBy('rennUhrzeit')
            ->first();

        if ($sliteShowRace) {
            // sliteShowResult zurücksetzen
            $sliteShowRace->sliteShowResult = 0;
            $sliteShowRace->save();

            // Speichere das Datum und die Uhrzeit als zuletzt angezeigt
            session([
                'lastShownResultRennDatum' => $sliteShowRace->rennDatum,
                'lastShownResultRennUhrzeit' => $sliteShowRace->rennUhrzeit,
            ]);
            return $sliteShowRace;
        }

        $newRace = $query
            ->orderBy('rennDatum')
            ->orderBy('rennUhrzeit')
            ->first();

        if ($newRace) {
            // Speichere das Datum und die Uhrzeit als zuletzt angezeigt
            session([
                'lastShownResultRennDatum' => $newRace->rennDatum,
                'lastShownResultRennUhrzeit' => $newRace->rennUhrzeit,
            ]);
            return $newRace;
        }
        return null;
    }

    /**
     * Prüft, ob ein Rennen mit liveStream=1 existiert und leitet ggf. weiter.
     * Gibt ggf. ein Redirect-Response zurück, sonst null.
     */
    private function checkAndRedirectLiveStream()
    {
        $event = $this->event;
        $raceWithLiveStream = \App\Models\Race::where('event_id', $event?->event_id)
            ->where('liveStream', 1)
            ->orderBy('rennDatum')
            ->orderBy('rennUhrzeit')
            ->first();

        if ($raceWithLiveStream) {
            // Weiterleitung zur Livestream-Seite
            return redirect()->route('presentation.liveStream');
        }
        return null;
    }

    public function welcome()
    {
        $this->initEventAndRegattaStarted();
        $event = $this->event;

        if ($redirect = $this->checkAndRedirectLiveStream()) {
            return $redirect;
        }

        // Prüfe, ob die Regatta bereits gestartet ist (Session-Variable)
        if ($this->regattaStarted) {
            return redirect()->route('presentation.laneOccupancy');
        }

        return view('presentation.welcome', compact('event'));
    }

    public function teams(Request $request)
    {
        $this->initEventAndRegattaStarted();
        $event = $this->event;

        if ($redirect = $this->checkAndRedirectLiveStream()) {
            return $redirect;
        }

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
        $this->initEventAndRegattaStarted();
        $event = $this->event;

        if ($redirect = $this->checkAndRedirectLiveStream()) {
            return $redirect;
        }

        $eventId = $event->event_id;

        $newResult = $this->checkForNewRaceResult();
        if ($newResult) {
            return redirect()->route('presentation.newResult', ['raceId' => $newResult->id]);
        }

        // Wenn die Regatta noch nicht gestartet wurde, zeige alle Rennen mit status == 2 (sichtbar)
        if (!$this->regattaStarted) {
            $races = Race::with(['lanes.regattaTeam'])
                ->where('event_id', $eventId)
                ->where('status', 2)
                ->where('visible', 1)
                ->orderBy('level')
                ->orderBy('rennDatum')
                ->orderBy('rennZeit')
                ->get();

            if ($races->isEmpty()) {
                return redirect()->route('presentation.result');
            }

            $activeLevel = $races->first() ? $races->first()->level : null;

            return view('presentation.laneOccupancy', [
                'races' => $races,
                'event' => $event,
                'activeLevel' => $activeLevel,
            ]);
        }

        // Aktives Level bestimmen: niedrigstes Level mit mind. einem Rennen status==2
        $activeLevel = Race::where('event_id', $eventId)
            ->where('status', 2)
            ->where('visible', 1)
            ->orderBy('level')
            ->value('level');

        if ($activeLevel === null) {
            // Wenn kein aktives Level gefunden wurde, direkt zu den Ergebnissen
            return redirect()->route('presentation.result');
        }

        // Anzahl der noch offenen Rennen im aktuellen Abschnitt (status==2)
        $openRacesCount = Race::where('event_id', $eventId)
            ->where('level', $activeLevel)
            ->where('status', 2)
            ->where('visible', 1)
            ->count();

        // IDs der Levels, die angezeigt werden sollen
        $levelsToShow = [$activeLevel];

        // Wenn 2 oder weniger Rennen offen sind, ermittle das nächsthöhere Level mit offenen Rennen
        if ($openRacesCount <= 1) {
            $nextLevel = Race::where('event_id', $eventId)
                ->where('level', '>', $activeLevel)
                ->where('status', 2)
                ->where('visible', 1)
                ->orderBy('level')
                ->value('level');
            if ($nextLevel !== null) {
                $levelsToShow[] = $nextLevel;
            }
        }

        // Alle offenen Rennen (status==2) der relevanten Abschnitte laden
        $races = Race::with(['lanes.regattaTeam'])
            ->where('event_id', $eventId)
            ->whereIn('level', $levelsToShow)
            ->where('status', 2)
            ->where('visible', 1)
            ->orderBy('level')
            ->orderBy('rennDatum')
            ->orderBy('rennZeit')
            ->get();

        if ($races->isEmpty()) {
            return redirect()->route('presentation.result');
        }

        $activeLevel = $races->first() ? $races->first()->level : null;

        return view('presentation.laneOccupancy', [
            'races' => $races,
            'event' => $event,
            'activeLevel' => $activeLevel,
        ]);
    }

    public function result()
    {
        $this->initEventAndRegattaStarted();
        $event = $this->event;

        if ($redirect = $this->checkAndRedirectLiveStream()) {
            return $redirect;
        }

        $eventId = $event->event_id;

        // Prüfung auf neues Ergebnis und ggf. Redirect
        $newResult = $this->checkForNewRaceResult();
        if ($newResult) {
            return redirect()->route('presentation.newResult', ['raceId' => $newResult->id]);
        }

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
        $this->initEventAndRegattaStarted();
        $event = $this->event;

        if ($redirect = $this->checkAndRedirectLiveStream()) {
            return $redirect;
        }

        // Nur anzeigen, wenn die Regatta noch nicht gestartet wurde
        if ($this->regattaStarted) {
            return redirect()->route('presentation.welcome');
        }

        return view('presentation.video', [
            'event' => $event,
        ]);
    }

    public function liveStream()
    {
        $this->initEventAndRegattaStarted();
        $event = $this->event;
        return view('presentation.liveStream', [
            'event' => $event,
        ]);
    }

    public function table()
    {
        $this->initEventAndRegattaStarted();
        $event = $this->event;

        if ($redirect = $this->checkAndRedirectLiveStream()) {
            return $redirect;
        }

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
        $this->initEventAndRegattaStarted();
        $event = $this->event;

        if ($redirect = $this->checkAndRedirectLiveStream()) {
            return $redirect;
        }

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
        $this->initEventAndRegattaStarted();
        $event = $this->event;

        if ($redirect = $this->checkAndRedirectLiveStream()) {
            return $redirect;
        }

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

    // Neue Methode zum Anzeigen eines neuen Ergebnisses
    public function newResult($raceId)
    {
        $this->initEventAndRegattaStarted();
        $event = $this->event;

        $race = \App\Models\Race::with(['lanes.regattaTeam'])
            ->where('id', $raceId)
            ->where('status', 4)
            ->where('visible', 1)
            ->first();

        // Prüfe, ob eine Tabelle vorhanden ist
        $hasTable = \App\Models\Tabele::where('event_id', $event?->event_id)
            ->where('tabelleVisible', 1)
            ->exists();

        if (!$race) {
            // Falls das Rennen nicht gefunden wird, weiter zur normalen Ergebnisanzeige
            return redirect()->route('presentation.result');
        }

        // Ziel-URL für Weiterleitung bestimmen
        // Wenn das Rennen eine tabele_id hat, leite nach newTable weiter, sonst wie gehabt
        if (!empty($race->tabele_id)) {
            $redirectUrl = route('presentation.newTable', ['tableId' => $race->tabele_id]);
        } else {
            $redirectUrl = route('presentation.table');
        }

        return view('presentation.newResult', [
            'race' => $race,
            'event' => $event,
            'redirectUrl' => $redirectUrl,
        ]);
    }

    // Neue Präsentationsseite für eine neue Tabelle
    public function newTable($tableId)
    {
        $this->initEventAndRegattaStarted();
        $event = $this->event;

        $table = \App\Models\Tabele::with(['tabeledataShows.getMannschaft'])
            ->where('id', $tableId)
            ->where('tabelleVisible', 1)
            ->first();

        $tables = Tabele::with(['tabeledataShows.getMannschaft'])
            ->where('id', $tableId)
            ->where('tabelleVisible', 1)
            ->orderBy('id')
            ->get()
            // Filtere Tabellen ohne Platzierungen heraus
            ->filter(function($table) {
                return $table->tabeledataShows && $table->tabeledataShows->count() > 0;
            })
            ->values();

        // Nach Anzeige der Tabelle weiter zur normalen Tabellenpräsentation oder Video
        $redirectUrl = route('presentation.video');

        if (!$tables) {
            return redirect($redirectUrl);
        }

        return view('presentation.newTable', [
            'tables' => $tables,
            'event' => $event,
            'redirectUrl' => $redirectUrl,
        ]);
    }

    // AJAX-Check, ob Livestream noch aktiv ist
    public function checkLiveStream()
    {
        $this->initEventAndRegattaStarted();
        $event = $this->event;

        $active = \App\Models\Race::where('event_id', $event?->event_id)
            ->where('liveStream', 1)
            ->exists();

        return response()->json(['active' => $active]);
    }
}
