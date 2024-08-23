<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Lane;
use App\Models\Race;
use Illuminate\Support\Carbon;

// use Illuminate\Http\Request;

class OBSLiveController extends Controller
{
    public function result()
    {
        $events = Event::join('races as ra' , 'events.id' , '=' , 'ra.event_id')
            ->where('events.regatta' , '1')
            ->where('events.verwendung' , 0)
            ->orderby('events.datumvon' , 'desc')
            ->limit(1)
            ->get();

        // Es wird $event->event_id verwendet weil die id in events und races vorhanden wird und events->id mit races->id überschrieben
        $eventId=0;
        foreach($events as $event) {
            $eventId=$event->event_id;
        }

        $races = Race::where('event_id', $eventId)
            ->where('visible' , 1)
            ->where('status' , 4)
            ->orderby('rennDatum')
            ->orderby('rennUhrzeit', 'desc')
            ->limit(1)
            ->get();

        if($races->count()==1) {
            foreach($races as $race) {
                $raceId=$race->id;
            }
            $race = Race::find($raceId);

            $lanes = Lane::where('rennen_id', $raceId)
                ->orderBy('platz')
                ->get();

            if($race->veroeffentlichungUhrzeit < Carbon::now()->toTimeString() || $race->rennDatum < Carbon::now()->toDateString()) {
                return view('obsLive.result')->with(
                    [
                        'race' => $race,
                        'lanes' => $lanes,
                        'victoCremony' => 0,
                    ]);
            }
            else {
                return view('obsLive.result')->with(
                    [
                        'race' => $race,
                        'lanes' => $lanes,
                        'victoCremony' => 1,
                    ]);
            }
        }

        return view('obsLive.emty');
    }

    public function laneOccupancy()
    {
        $events = Event::join('races as ra' , 'events.id' , '=' , 'ra.event_id')
            ->where('events.regatta' , '1')
            ->where('events.verwendung' , 0)
            ->orderby('events.datumvon' , 'desc')
            ->limit(1)
            ->get();

        // Es wird $event->event_id verwendet weil die id in events und races vorhanden wird und events->id mit races->id überschrieben
        $eventId=0;
        foreach($events as $event) {
            $eventId=$event->event_id;
        }

        $races = Race::where('event_id', $eventId)
            ->where('visible' , 1)
            ->where('status' , 2)
            ->orderby('rennDatum')
            ->orderby('rennUhrzeit')
            ->limit(1)
            ->get();

        if($races->count()==1) {
            foreach($races as $race) {
                $raceId=$race->id;
            }
            $race = Race::find($raceId);

            $lanes = Lane::where('rennen_id', $raceId)
                ->orderBy('bahn')
                ->get();

            return view('obsLive.laneOccupancy')->with(
                [
                    'race'         => $race,
                    'lanes'        => $lanes,
                ]);
        }

        return view('obsLive.emty');
    }

    public function nextRace()
    {
        $events = Event::join('races as ra' , 'events.id' , '=' , 'ra.event_id')
            ->where('events.regatta' , '1')
            ->where('events.verwendung' , 0)
            ->orderby('events.datumvon' , 'desc')
            ->limit(1)
            ->get();

        // Es wird $event->event_id verwendet weil die id in events und races vorhanden wird und events->id mit races->id überschrieben
        $eventId=0;
        foreach($events as $event) {
            $eventId=$event->event_id;
        }

        $races = Race::where('event_id', $eventId)
            ->where('visible' , 1)
            ->where('status' , 4)
            ->orderby('rennDatum')
            ->orderby('rennUhrzeit', 'desc')
            ->limit(1)
            ->get();

        if($races->count()==1) {
            foreach($races as $race) {
                $raceId=$race->id;
            }
            $race = Race::find($raceId);

            return view('obsLive.nextRace')->with(
                    [
                        'race' => $race
                    ]);
        }
        else {
            return view('obsLive.emty');
        }
    }
}
