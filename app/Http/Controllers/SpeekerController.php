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

            $races = Race::where('event_id', $eventId)
                ->where('visible' , 1)
                ->where('status', '<=', 2)
                ->whereDate('rennDatum', Carbon::today())
                ->orderby('rennUhrzeit')
                ->limit(2)
                ->get();

            if($races->count()==0){
                return view('speeker.show')->with(
                    [
                        'raceNext1'     => Null,
                        'raceNext2'     => Null,
                        'raceResoult1'  => Null,
                        'raceResoult2'  => Null,
                        'lanesNext1'    => Null,
                        'lanesNext2'    => Null,
                        'lanesResoult1' => Null,
                        'lanesResoult2' => Null,
                        'victoCremony1' => 0,
                        'victoCremony2' => 0,
                        'raceChoose'    => Null,
                        'vorId'         => 0,
                        'nachId'        => 0
                    ]);
            }

            $raceChooses = Race::where('event_id', $eventId)
                ->where('visible' , 1)
                ->whereDate('rennDatum', Carbon::today())
                ->orderby('rennUhrzeit')
                ->get();

            $counter = 0;
            foreach($races as $race) {
                $counter++;
                if($counter == 1) {
                    // This is the first iteration
                    $raceNextId1 = $race->id;
                    $raceNext1 = $race;
                }
                if($counter == count($races)) {
                    // This is the last iteration
                    $raceNextId2 = $race->id;
                    $raceNext2 = $race;
                    if($counter>1) {
                        $vorId = $raceNextId2;
                    }
                }
            }

            if($nachId==0) {
                $nach=0;
                foreach ($raceChooses as $raceChoose) {
                    if($raceChoose->id==$raceNextId1){
                        if($nach>0) {
                            $nachId = $nach;
                        }
                        break;
                    }
                    $nach = $raceChoose->id;
                }
            }

            $races = Race::where('event_id', $eventId)
                ->where('visible' , 1)
                ->where('status', '>=', 3)
                ->where('status', '<=', 4)
                ->whereDate('rennDatum', Carbon::today())
                ->orderby('status')
                ->orderby('rennUhrzeit', 'desc')
                ->limit(2)
                ->get();

            $counter = 0;
            foreach($races as $race) {
                $counter++;
                if ($counter == 1) {
                    $raceResoultId1 = $race->id;
                    $raceResoult1 = $race;
                }
                if ($counter == count($races)) {
                    $raceResoultId2 = $race->id;
                    $raceResoult2 = $race;
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

            $raceChooses = Race::where('event_id', $race1->event_id)
                ->where('visible' , 1)
                ->whereDate('rennDatum', Carbon::today())
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

            $nach=0;
            foreach ($raceChooses as $raceChoose) {
                if($raceChoose->id==$raceNextId1){
                    if($nach>0) {
                        $nachId = $nach;
                    }
                    break;
                }
                $nach = $raceChoose->id;
            }

            if($nach==0) {
                foreach ($raceChooses as $raceChoose) {
                    if ($raceChoose->id == $raceResoult1) {
                        if ($nach > 0) {
                            $nachId = $nach;
                        }
                        break;
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
                $victoCremony1 = 0;
            }
        }
        else {
            $raceResoult1 = Null;
            $lanesResoult1 = Null;
        }

        if(isset($raceResoultId2)) {
            $lanesResoult2 = Lane::where('rennen_id', $raceResoultId2)
                ->orderBy('platz')
                ->get();
            if($raceResoult2->veroeffentlichungUhrzeit < Carbon::now()->toTimeString() || $raceResoult2->rennDatum < Carbon::now()->toDateString()) {
                $victoCremony2 = 0;
            }
        }
        else {
            $raceResoult2 = Null;
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
                    'raceChooses'   => $raceChooses,
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

        return view('speeker.showTeam')->with(
          [
              'teamId' => $teamId,
              'raceId' => $raceId,
              'team'   => $team,
              'teamsChoose' => $teamsChoose,
              'lanes'  => $lanes,
              'race' => $race,
              'vorId'  => 0,
              'nachId' => 0
          ]);
    }

    public function teamChoose(Request $request)
    {
        //dd($request->teamId, $request->raceId);

        return redirect()->route('speeker.teamShow', [
            'teamId' => $request->teamId,
            'raceId' => $request->raceId
        ]);
    }
}
