<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Lane;
use App\Models\Race;
use App\Models\RegattaTeam;
use App\Models\Tabele;
use App\Models\Tabledata;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SpeekerController extends Controller
{
    public function __construct()
    {
        $this->currentDate = Carbon::now()->toDateString();
        $this->currentTime = Carbon::now()->toTimeString();
        //Temp: Testdaten
        //$this->currentDate = "2023-08-26"; // Zu Testzwecken ein festes Datum Datum Format beachten
        //$this->currentTime = "06:00:00"; // Zu Testzwecken eine feste Uhrzeit Zeitformat beachten
    }

    public function show($speekerId = Null)
    {
        $vorId=0;
        $nachId=0;
        if (is_Null($speekerId)) {
            $event = Event::join('races as ra' , 'events.id' , '=' , 'ra.event_id')
                ->where('events.regatta' , '1')
                ->where('events.verwendung' , 0)
                ->orderby('events.datumvon' , 'desc')
                ->first();

            $eventId=$event->event_id;
            $now=Carbon::now()->toDateString();

            if($now < $event->datumvon) {
                $now = $event->datumvon;
            }

            if($now > $event->datumbis) {
                $now = $event->datumbis;
            }

            $races = Race::where('event_id', $eventId)
                ->where('visible' , 1)
                ->where('status', '<=', 3)
                ->where('status', '!=', 4)
                ->whereDate('rennDatum', $now)
                ->orderby('rennUhrzeit')
                ->limit(2)
                ->get();

            $racesResoult = Race::where('event_id', $eventId)
                ->where('visible' , 1)
                ->where('status', 4)
                ->whereDate('rennDatum', $now)
                ->orderby('rennUhrzeit' , 'desc')
                ->limit(2)
                ->get();

            if($races->count()==0 && $racesResoult->count()==0) {
                return view('speeker.empty');
            }

            $racesChoose = Race::where('event_id', $eventId)
                ->where('visible' , 1)
                ->whereDate('rennDatum', $now)
                ->orderby('rennUhrzeit')
                ->get();

            if($racesResoult->count()>0) {
                $counter = 0;
                foreach($racesResoult as $raceResoult) {
                    $counter++;
                    if($counter == 1) {
                        $raceResoultId1 = $raceResoult->id;
                        $raceResoult1   = $raceResoult;
                        $raceNextId1    = $raceResoult->id;
                        $raceNext1      = $raceResoult;
                        $raceResoultId2 = Null;
                        $raceResoult2   = Null;
                    }
                    if($counter == count($racesResoult) && $counter > 1) {
                        $raceResoultId2 = $raceResoult->id;
                        $raceResoult2 = $raceResoult;
                    }
                }
            }
            else{
                $raceResoultId1 = Null;
                $raceResoult1   = Null;
                $raceResoultId2 = Null;
                $raceResoult2   = Null;
            }

            if($races->count()==0){
                $raceResoultId3 = $raceResoultId2;
                $raceResoult3   = $raceResoult2;
                $raceResoultId2 = $raceResoultId1;
                $raceResoult2   = $raceResoult1;
                $raceResoultId1 = $raceResoultId3;
                $raceResoult1   = $raceResoult3;
            }

            If($racesResoult->count()>0) {
                $counter = 0;
                foreach($races as $race) {
                    $counter++;
                    if ($counter == 1) {
                        // This is the first iteration
                        $raceNextId2 = $race->id;
                        $raceNext2 = $race;
                        $raceResoultId2 = Null;
                        $raceResoult2   = Null;
                    }
                    if ($counter == count($races)) {
                        // This is the last iteration
                        if ($counter > 1) {
                            $vorId = $raceNextId2;
                        }
                    }
                }
            }
            else{
                $counter = 0;
                foreach($races as $race) {
                    $counter++;
                    if ($counter == 1) {
                        // This is the first iteration
                        $raceNextId1 = $race->id;
                        $raceNext1 = $race;
                        $raceResoultId1 = Null;
                        $raceResoult1   = Null;
                    }
                    if ($counter == count($races)) {
                        // This is the last iteration
                        $raceNextId2 = $race->id;
                        $raceNext2 = $race;
                        $raceResoultId2 = Null;
                        $raceResoult2   = Null;
                        if ($counter > 1) {
                            $vorId = $raceNextId2;
                        }
                    }
                }
            }

            if($races->count()==0){
                $raceNextId1 = Null;
                $raceNext1   = Null;
                $raceNextId2 = Null;
                $raceNext2   = Null;
                //$vorId       = Null;
            }

            $nach=0;
            if($raceResoultId1!=Null) {
                $raceResoultId1 = $raceResoult1->id;
                foreach ($racesChoose as $raceChoose) {
                    if ($raceChoose->id == $raceResoultId1) {
                        if ($nach > 0) {
                            $nachId = $nach;
                            break;
                        }
                    }
                    $nach = $raceChoose->id;
                }
            }

            if($nachId==Null){
               if($raceNextId1!=Null) {
                   $raceNextId1 = $raceNext1->id;
                    foreach ($racesChoose as $raceChoose) {
                        if ($raceChoose->id == $raceNextId1) {
                            if ($nach > 0) {
                                $nachId = $nach;
                                break;
                            }
                        }
                        $nach = $raceChoose->id;
                    }
               }
            }

        }
        else {
            $race1 = Race::find($speekerId);
            $race2 = Race::where('event_id', $race1->event_id)
                ->where('visible', 1)
                ->whereNot('id', $race1->id)
                ->where('rennDatum', $race1->rennDatum)
                ->where('rennUhrzeit', '>=', $race1->rennUhrzeit)
                ->orderby('rennDatum')
                ->orderby('rennUhrzeit')
                ->first();

            $racesChoose = Race::where('event_id', $race1->event_id)
                ->where('visible' , 1)
                ->whereDate('rennDatum', $race1->rennDatum)
                ->orderby('rennUhrzeit')
                ->get();

            $event = Event::find($race1->event_id);

            if ($race1->status <= 3) {
                $raceNextId1 = $race1->id;
                $raceNext1 = $race1;
                if($race2!=Null){
                    $vorId = $race2->id;
                    if ($race2->status == 4) {
                        dd('a1');
                        $raceResoultId1 = $race2->id;
                        $raceResoult1 = $race2;
                        $raceResoultId2 = Null;
                        $raceResoult2 = Null;
                        $raceNextId2 = $race1->id;;
                        $raceNext2 = $race1;
                    }
                    if ($race2->status <= 3) {
                        $raceNextId2 = $race2->id;
                        $raceNext2 = $race2;
                        $raceResoultId1 = Null;
                        $raceResoult1 = Null;
                        $raceResoultId2 = Null;
                        $raceResoult2 = Null;
                    }
                }
                else {
                    $raceNextId2 = Null;
                    $raceNext2 = Null;
                    $raceResoultId1 = Null;
                    $raceResoult1 = Null;
                    $raceResoultId2 = Null;
                    $raceResoult2 = Null;
                }
            }

            if ($race1->status == 4) {
                $raceResoultId1 = $race1->id;
                $raceResoult1   = $race1;
                $raceNextId1    = $race1->id;
                $raceNext1      = $race1;
                if($race2!=Null) {
                    $vorId = $race2->id;
                    if ($race2->status == 4) {
                        $raceResoultId2 = $race2->id;
                        $raceResoult2 = $race2;
                        $raceNextId2 = $race2->id;
                        $raceNext2 = $race2;
                    }
                    if ($race2->status <= 3) {
                        $raceNextId2 = $race2->id;
                        $raceNext2 = $race2;
                        $raceResoultId2 = Null;
                        $raceResoult2 = Null;
                    }
                }
                else{
                    $raceNextId2 = Null;
                    $raceNext2 = Null;
                    $raceResoultId2 = Null;
                    $raceResoult2 = Null;
                }
            }

            $nach=0;
            if($raceResoultId1!=Null) {
                $raceResoultId1 = $raceResoult1->id;
                foreach ($racesChoose as $raceChoose) {
                    if ($raceChoose->id == $raceResoultId1) {
                        if ($nach > 0) {
                            $nachId = $nach;
                            break;
                        }
                      }
                    if($raceChoose->id!=$raceNextId2){
                        $nach = $raceChoose->id;
                    }
                }
            }

            if($nach==0 && $raceNextId1!=Null) {
                foreach ($racesChoose as $raceChoose) {
                    if($raceChoose->id==$raceNextId1){
                        if($nach>0) {
                            $nachId = $nach;
                            break;
                        }
                    }
                    $nach = $raceChoose->id;
                }
            }
        }

        if(isset($raceNextId1)) {
            $lanesNext1= Lane::where('rennen_id', $raceNextId1)
                ->orderBy('bahn')
                ->get();
        }
        else {
            $lanesNext1 = Null;
        }

        if(isset($raceNextId2)) {
            $lanesNext2= Lane::where('rennen_id', $raceNextId2)
                ->orderBy('bahn')
                ->get();
        }
        else {
            $lanesNext2 = Null;
        }

        $victoCremony1 = 1;
        $victoCremony2 = 1;

        if(isset($raceResoultId1)) {
            $lanesResoult1 = Lane::where('rennen_id', $raceResoultId1)
                ->orderBy('platz')
                ->get();

            if(($raceResoult1->veroeffentlichungUhrzeit > $this->currentTime && $raceResoult1->rennDatum == $this->currentDate)
                || $raceResoult1->tabelleDatumVon > $this->currentDate){
                $victoCremony1 = 0; // 0 - nicht anzeigen, 1 - anzeigen
            }
            else {
                  $raceNext1   = $raceResoult1;
                  $raceNextId1 = $raceResoult1->id;
                  $lanesNext1  = Lane::where('rennen_id', $raceNextId1)
                      ->orderBy('bahn')
                      ->get();
            }
        }
        else {
            $raceResoult1  = Null;
            $lanesResoult1 = Null;
        }

        if(isset($raceResoultId2)) {
            $lanesResoult2 = Lane::where('rennen_id', $raceResoultId2)
                ->orderBy('platz')
                ->get();
            if(($raceResoult2->veroeffentlichungUhrzeit > $this->currentTime && $raceResoult2->rennDatum == $this->currentDate)
                || $raceResoult2->tabelleDatumVon > $this->currentDate){
                $victoCremony2 = 0; // 0 - nicht anzeigen, 1 - anzeigen
                $lanesNext2= Lane::where('rennen_id', $raceNextId2)
                    ->orderBy('bahn')
                    ->get();
            }
            else{
                $raceNext2   = $raceResoult2;
                $raceNextId2 = $raceResoult2->id;
                $lanesNext2  = Lane::where('rennen_id', $raceNextId2)
                    ->orderBy('bahn')
                    ->get();
            }
        }
        else {
            $raceResoult2  = Null;
            $lanesResoult2 = Null;
        }

        $victoCremonyTable1 = 1;
        $victoCremonyTable2 = 1;

        if ($raceResoult1 && Tabele::find($raceResoult1->tabele_id)?->tabelleVisible == 1) {

            $tableResoult1 = Tabele::find($raceResoult1->tabele_id);

            if (($tableResoult1->finaleAnzeigen > $this->currentTime && $tableResoult1->tabelleDatumVon == $this->currentDate) || $tableResoult1->tabelleDatumVon > $this->currentDate) {
                $victoCremonyTable1 = 0; // 0 - nicht anzeigen, 1 - anzeigen
            }

            // Alle Tabledata-Einträge holen und Platz berechnen
            $tabeledatas1 = Tabledata::where('tabele_id', $raceResoult1->tabele_id)
                ->orderBy('punkte', 'desc')
                ->orderBy('buchholzzahl', 'desc')
                ->orderBy('zeit')
                ->orderBy('hundert')
                ->get()
                ->values()
                ->map(function ($item, $key) {
                    $item->platz = $key + 1;
                    return $item;
                });

            // Mannschaften-IDs aus den Lanes holen
            $mannschaftIds = Lane::where('rennen_id', $raceResoult1->id)->pluck('mannschaft_id')->toArray();

            // Nachträglich filtern
            $tabeledatas1 = $tabeledatas1->filter(function ($item) use ($mannschaftIds) {
                return in_array($item->mannschaft_id, $mannschaftIds);
            })->values();
        }
        else{
            $tabeledatas1 = Null;
        }


        if ($raceResoult2 && Tabele::find($raceResoult2->tabele_id)?->tabelleVisible == 1) {

            $tableResoult2 = Tabele::find($raceResoult2->tabele_id);
            if (($tableResoult2->finaleAnzeigen > $this->currentTime && $tableResoult2->tabelleDatumVon == $this->currentDate) || $tableResoult2->tabelleDatumVon > $this->currentDate) {
                $victoCremonyTable2 = 0;
            }

            // Alle Tabledata-Einträge holen und Platz berechnen
            $tabeledatas2 = Tabledata::where('tabele_id', $raceResoult2->tabele_id)
                ->orderBy('punkte', 'desc')
                ->orderBy('buchholzzahl', 'desc')
                ->orderBy('zeit')
                ->orderBy('hundert')
                ->get()
                ->values()
                ->map(function ($item, $key) {
                    $item->platz = $key + 1;
                    return $item;
                });

            // Mannschaften-IDs aus den Lanes holen
            $mannschaftIds = Lane::where('rennen_id', $raceResoult2->id)->pluck('mannschaft_id')->toArray();

            // Nachträglich filtern
            $tabeledatas2 = $tabeledatas2->filter(function ($item) use ($mannschaftIds) {
                return in_array($item->mannschaft_id, $mannschaftIds);
            })->values();
        }
        else{
            $tabeledatas2 = Null;
        }

        return view('speeker.show')->with(
                [
                    'event'         => $event,
                    'raceNext1'     => $raceNext1,
                    'raceNext2'     => $raceNext2,
                    'raceResoult1'  => $raceResoult1,
                    'raceResoult2'  => $raceResoult2,
                    'tabeledatas1'  => $tabeledatas1,
                    'tabeledatas2'  => $tabeledatas2,
                    'lanesNext1'    => $lanesNext1,
                    'lanesNext2'    => $lanesNext2,
                    'lanesResoult1' => $lanesResoult1,
                    'lanesResoult2' => $lanesResoult2,
                    'victoCremony1' => $victoCremony1,
                    'victoCremony2' => $victoCremony2,
                    'victoCremonyTable1' => $victoCremonyTable1,
                    'victoCremonyTable2' => $victoCremonyTable2,
                    'racesChoose'        => $racesChoose,
                    'vorId'              => $vorId,
                    'nachId'             => $nachId,
                ]);
    }

    public function choose(Request $request)
    {
        $speekerId = $request->speekerId;

        return redirect()->route('speeker.show', ['speekerId' => $speekerId]);
    }

    public function teamShow($teamId, $raceId)
    {
        $event = Event::join('races as ra' , 'events.id' , '=' , 'ra.event_id')
            ->where('events.regatta' , '1')
            ->where('events.verwendung' , 0)
            ->orderby('events.datumvon' , 'desc')
            ->first();

        $eventId=$event->event_id;

        $race = Race::find($raceId);

        $team = RegattaTeam::find($teamId);

        $teamsChoose = RegattaTeam::where('regatta_id', $eventId)
            ->orderBy('teamname')
            ->get();

        $lanes=Null;
        $tabeledatas=Null;
        $victoCremony = 1;
        $victoCremonyTable = 1;

        if($race->status == 2) {
            $lanes = Lane::where('rennen_id', $raceId)
                ->orderBy('bahn')
                ->get();
        }

        if($race->status == 4) {
            $lanes = Lane::where('rennen_id', $raceId)
                ->orderBy('platz')
                ->get();

            if ($race && Tabele::find($race->tabele_id)?->tabelleVisible == 1) {
                // Alle Tabledata-Einträge holen und Platz berechnen
                $tabeledatas = Tabledata::where('tabele_id', $race->tabele_id)
                    ->orderBy('punkte', 'desc')
                    ->orderBy('buchholzzahl', 'desc')
                    ->orderBy('zeit')
                    ->orderBy('hundert')
                    ->get()
                    ->values()
                    ->map(function ($item, $key) {
                        $item->platz = $key + 1;
                        return $item;
                    });

                // Mannschaften-IDs aus den Lanes holen
                $mannschaftIds = Lane::where('rennen_id', $race->id)->pluck('mannschaft_id')->toArray();

                // Nachträglich filtern
                $tabeledatas = $tabeledatas->filter(function ($item) use ($mannschaftIds) {
                    return in_array($item->mannschaft_id, $mannschaftIds);
                })->values();
            }
            else{
                $tabeledatas = Null;
            }
        }

        if (($race->veroeffentlichungUhrzeit > $this->currentTime && $race->rennDatum == $this->currentDate) || $race->rennDatum > $this->currentDate) {
            $victoCremony = 0;
        }


        $table = Tabele::find($race->tabele_id);
        if (($table && $table->finaleAnzeigen > $this->currentTime && $table->tabelleDatumVon == $this->currentDate) || $table->tabelleDatumVon > $this->currentDate) {
            $victoCremonyTable = 0; // 0 - nicht anzeigen, 1 - anzeigen
        }

        return view('speeker.showTeam')->with(
           [
              'event'             => $event,
              'teamId'            => $teamId,
              'raceId'            => $raceId,
              'team'              => $team,
              'teamsChoose'       => $teamsChoose,
              'lanes'             => $lanes,
              'race'              => $race,
              'tabele'            => $table,
              'vorId'             => 0,
              'nachId'            => 0,
              'victoCremony'      => $victoCremony,
              'victoCremonyTable' => $victoCremonyTable,
              'tabeledatas'       => $tabeledatas,
           ]);
    }

    public function teamChoose(Request $request)
    {
        return redirect()->route('speeker.teamShow', [
            'teamId' => $request->teamId,
            'raceId' => $request->raceId
        ]);
    }

    public function tabeleShow($tableId, $raceId)
    {
        $event = Event::join('races as ra' , 'events.id' , '=' , 'ra.event_id')
            ->where('events.regatta' , '1')
            ->where('events.verwendung' , 0)
            ->orderby('events.datumvon' , 'desc')
            ->first();

        $eventId=$event->event_id;

        $race = Race::find($raceId);

        $lanes=Null;
        $tabeledatas=Null;
        $victoCremony = 1;
        $victoCremonyTable = 1;
        $victoCremonyTableShow = 1;

        if($race->status == 2) {
            $lanes = Lane::where('rennen_id', $raceId)
                ->orderBy('bahn')
                ->get();
        }

        if($race->status == 4) {
            $lanes = Lane::where('rennen_id', $raceId)
                ->orderBy('platz')
                ->get();

            $table = Tabele::find($race->tabele_id);

            if ($race && $table->tabelleVisible == 1) {
                // Alle Tabledata-Einträge holen und Platz berechnen
                $tabeledatas = Tabledata::where('tabele_id', $race->tabele_id)
                    ->orderBy('punkte', 'desc')
                    ->orderBy('buchholzzahl', 'desc')
                    ->orderBy('zeit')
                    ->orderBy('hundert')
                    ->get()
                    ->values()
                    ->map(function ($item, $key) {
                        $item->platz = $key + 1;
                        return $item;
                    });

                // Mannschaften-IDs aus den Lanes holen
                $mannschaftIds = Lane::where('rennen_id', $race->id)->pluck('mannschaft_id')->toArray();

                // Nachträglich filtern
                $tabeledatas = $tabeledatas->filter(function ($item) use ($mannschaftIds) {
                    return in_array($item->mannschaft_id, $mannschaftIds);
                })->values();
            }
            else{
                $tabeledatas = Null;
            }
        }

        if(($race->veroeffentlichungUhrzeit > Carbon::now()->toTimeString() && $race->rennDatum == Carbon::now()->toDateString()) || $race->rennDatum > Carbon::now()->toDateString()){
            $victoCremony = 0; // 0 - nicht anzeigen, 1 - anzeigen
        }

        if(($table->finaleAnzeigen > Carbon::now()->toTimeString() && $table->tabelleDatumVon == Carbon::now()->toDateString() ) || $table->tabelleDatumVon > Carbon::now()->toDateString()){
            $victoCremonyTable = 0; // 0 - nicht anzeigen, 1 - anzeigen
        }

        $tabelChooses = Tabele::where('event_id', $eventId)
            ->where('tabelleVisible', 1)
            ->where(function($query) {
                $query->where([
                    ['finaleAnzeigen', '<', Carbon::now()->toTimeString()],
                    ['tabelleDatumVon', '=', Carbon::now()->toDateString()]
                ])
                    ->orWhere('tabelledatumVon', '<', Carbon::now()->toDateString());
            })
            ->orderBy('tabelleLevelVon')
            ->orderBy('ueberschrift')
            ->get();


        $tableShow = Tabele::find($tableId);

        if($tableShow->tabelleVisible == 1) {

            $tabeledataShows = Tabledata::where('tabele_id', $tableId)
                ->orderBy('punkte', 'desc')
                ->orderBy('buchholzzahl', 'desc')
                ->orderBy('zeit')
                ->orderBy('hundert')
                ->get()
                ->values()
                ->map(function ($item, $key) {
                    $item->platz = $key + 1;
                    return $item;
                });
        }
        else {
            $tabeledataShows = Null;
        }

        if(($tableShow->finaleAnzeigen > $this->currentTime && $tableShow->tabelleDatumVon == $this->currentDate ) || $tableShow->tabelleDatumVon > $this->currentDate){
            $victoCremonyTableShow = 0;
        }

        return view('speeker.showTable')->with(
            [
                'event'             => $event,
                'tableShow'         => $tableShow,
                'tableId'           => $tableId,
                'table'             => $table,
                'victoCremonyTableShow' => $victoCremonyTableShow,
                'raceId'            => $raceId,
                'tabelChooses'      => $tabelChooses,
                'lanes'             => $lanes,
                'race'              => $race,
                'victoCremony'      => $victoCremony,
                'victoCremonyTable' => $victoCremonyTable,
                'tabeledatas'       => $tabeledatas,
                'tabeledataShows'   => $tabeledataShows,
            ]);
    }

    public function tableChoose(Request $request)
    {
        return redirect()->route('speeker.tabeleShow', [
            'tableId' => $request->tableId,
            'raceId'  => $request->raceId
        ]);
    }
}
