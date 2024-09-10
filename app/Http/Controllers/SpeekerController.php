<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Lane;
use App\Models\Race;
use App\Models\RegattaTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SpeekerController extends Controller
{
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
                        $raceResoult1 = $raceResoult;
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
                        $raceNextId1 = Null;
                        $raceNext1 = Null;
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

            if ($race1->status <= 3) {
                $raceNextId1 = $race1->id;
                $raceNext1 = $race1;
                if($race2!=Null){
                    $vorId = $race2->id;
                    if ($race2->status == 4) {
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
                $raceResoult1 = $race1;
                $raceNextId1 = Null;
                $raceNext1 = Null;
                if($race2!=Null) {
                    $vorId = $race2->id;
                    if ($race2->status == 4) {
                        $raceResoultId2 = $race2->id;
                        $raceResoult2 = $race2;
                        $raceNextId2 = Null;
                        $raceNext2 = Null;
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
            if($raceResoult1->veroeffentlichungUhrzeit < Carbon::now()->toTimeString() || $raceResoult1->rennDatum < Carbon::now()->toDateString()) {
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
            if($raceResoult2->veroeffentlichungUhrzeit < Carbon::now()->toTimeString() || $raceResoult2->rennDatum < Carbon::now()->toDateString()) {
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

        return view('speeker.show')->with(
                [
                    'raceNext1'     => $raceNext1,
                    'raceNext2'     => $raceNext2,
                    'raceResoult1'  => $raceResoult1,
                    'raceResoult2'  => $raceResoult2,
                    'lanesNext1'    => $lanesNext1,
                    'lanesNext2'    => $lanesNext2,
                    'lanesResoult1' => $lanesResoult1,
                    'lanesResoult2' => $lanesResoult2,
                    'victoCremony1' => $victoCremony1,
                    'victoCremony2' => $victoCremony2,
                    'racesChoose'   => $racesChoose,
                    'vorId'         => $vorId,
                    'nachId'        => $nachId,
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
        $victoCremony = 1;
        if($race->status == 2) {
            $lanes = Lane::where('rennen_id', $raceId)
                ->orderBy('bahn')
                ->get();
        }

        if($race->status == 4) {
            $lanes = Lane::where('rennen_id', $raceId)
                ->orderBy('platz')
                ->get();
        }

        if($race->veroeffentlichungUhrzeit < Carbon::now()->toTimeString() || $race->rennDatum < Carbon::now()->toDateString()) {
            $victoCremony = 0;
        }

        return view('speeker.showTeam')->with(
          [
              'teamId'       => $teamId,
              'raceId'       => $raceId,
              'team'         => $team,
              'teamsChoose'  => $teamsChoose,
              'lanes'        => $lanes,
              'race'         => $race,
              'vorId'        => 0,
              'nachId'       => 0,
              'victoCremony' => $victoCremony
          ]);
    }

    public function teamChoose(Request $request)
    {
        return redirect()->route('speeker.teamShow', [
            'teamId' => $request->teamId,
            'raceId' => $request->raceId
        ]);
    }
}
