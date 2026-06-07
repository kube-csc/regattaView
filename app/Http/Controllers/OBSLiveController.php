<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Lane;
use App\Models\Race;
use App\Services\EventSelectionService;
use Illuminate\Support\Carbon;

class OBSLiveController extends Controller
{
    private EventSelectionService $eventSelectionService;
    public $eventId=0;

    public function __construct(EventSelectionService $eventSelectionService)
    {
        $this->eventSelectionService = $eventSelectionService;
    }
    public function result()
    {
        $eventId = $this->eventId();

        $races = Race::where('event_id', $eventId)
            ->where('visible', 1)
            ->where('status', 4)
            ->orderBy('aktuellLiveVideo' , 'desc')
            ->orderBy('rennDatum')
            ->orderBy('rennUhrzeit', 'desc')
            ->limit(1)
            ->get();

        if ($races->count() == 1) {
            foreach ($races as $race) {
                $raceId = $race->id;
            }
            $race = Race::find($raceId);

            $lanes = Lane::where('rennen_id', $raceId)
                ->where('platz', '>', 0)
                ->orderBy('platz')
                ->get();

            if (    $race->rennDatum < Carbon::now()->toDateString() ||
                 (
                    $race->rennDatum == Carbon::now()->toDateString() &&
                    $race->veroeffentlichungUhrzeit < Carbon::now()->toTimeString()
                 )
               )
            {
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

    public function resultall()
    {
        $eventId = $this->eventId();

        $race = Race::where('event_id', $eventId)
            ->where('visible', 1)
            ->where('status', 4)
            ->orderBy('aktuellLiveVideo' , 'desc')
            ->orderBy('rennDatum')
            ->orderBy('rennUhrzeit', 'desc')
            //->limit(1)
            //->get();
            ->first();

        //if ($races->count() == 1) {
        if ($race) {
            $lanes = Lane::where('rennen_id', $race->id)
                ->where('platz', '>', 0)
                ->orderBy('platz')
                ->get();

            return view('obsLive.result')->with(
                [
                    'race' => $race,
                    'lanes' => $lanes,
                    'victoCremony' => 0,
                ]);
        }

        return view('obsLive.emty');
    }

    public function laneOccupancy()
    {
        $eventId = $this->eventId();

        $races = Race::where('event_id', $eventId)
            ->where('visible', 1)
            ->where(function($query) {
                $query->where('status', 2)
                    ->orWhere(function($query) {
                        $query->where('aktuellLiveVideo', 1);
                    });
            })
            ->orderBy('aktuellLiveVideo' , 'desc')
            ->orderBy('rennDatum')
            ->orderBy('rennUhrzeit')
            ->limit(1)
            ->get();

        if ($races->count() == 1) {
            foreach ($races as $race) {
                $raceId = $race->id;
            }
            $race = Race::find($raceId);

            $lanes = Lane::where('rennen_id', $raceId)
                ->orderBy('bahn')
                ->get();

            return view('obsLive.laneOccupancy')->with(
                [
                    'race' => $race,
                    'lanes' => $lanes,
                ]);
        }

        return view('obsLive.emty');
    }

    public function nextRace()
    {
        $eventId = $this->eventId();

        $race = Race::where('event_id', $eventId)
            ->where('visible', 1)
            ->where(function($query) {
                $query->where('status', 2)
                    ->orWhere(function($query) {
                        $query->where('aktuellLiveVideo', 1);
                    });
            })
            ->orderBy('aktuellLiveVideo' , 'desc')
            ->orderBy('rennDatum')
            ->orderBy('rennUhrzeit')
            //->limit(1)
            ->first();

        //if ($races->count() == 1) {
        if ($race) {
            ///foreach ($races as $race) {
            ///    $raceId = $race->id;
            ///}
            ///$race = Race::find($raceId);
            //$race = Race::find($race->id);

            return view('obsLive.nextRace')->with(
                [
                    'race' => $race
                ]);
        }
        else {
            return view('obsLive.emty');
        }
    }

    public function currentRace()
    {
        $eventId = $this->eventId();

        $race = Race::where('event_id', $eventId)
            ->where('visible', 1)
            ->where('aktuellLiveVideo', 1)
            ->orderBy('aktuellLiveVideo' , 'desc')
            ->orderBy('rennDatum')
            ->orderBy('rennUhrzeit')
            ->first();

        if ($race) {

            //$race = Race::find($race->id);

            return view('obsLive.currentRace')->with(
                [
                    'race' => $race
                ]);
        }
        else {
            return view('obsLive.emty');
        }
    }

    public function eventId()
    {
        $event = $this->eventSelectionService->getNextRegattaEventWithAnmeldetext(14);

        if ($event) {
            return $event->id;
        }

        return 0;
    }

}
