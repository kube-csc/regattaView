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

        $race = Race::where('event_id', $eventId)
            ->where('visible', 1)
            ->where('status', 4)
            ->orderBy('aktuellLiveVideo' , 'asc')
            ->orderBy('rennDatum')
            ->orderBy('rennUhrzeit', 'desc')
            ->first();

        if (!$race) {
            return view('obsLive.emty');
        }

        $lanes = Lane::where('rennen_id', $race->id)
            ->where('platz', '>', 0)
            ->orderBy('platz')
            ->get();

        $now = Carbon::now();
        $releaseAt = Carbon::parse($race->rennDatum . ' ' . $race->veroeffentlichungUhrzeit);
        $victoCremony = $now->lt($releaseAt) ? 1 : 0;

        return view('obsLive.result')->with(
            [
                'race' => $race,
                'lanes' => $lanes,
                'victoCremony' => $victoCremony,
            ]);
    }

    public function resultall()
    {
        $eventId = $this->eventId();

        $race = Race::where('event_id', $eventId)
            ->where('visible', 1)
            ->where('status', 4)
            ->orderBy('aktuellLiveVideo' , 'asc')
            ->orderBy('rennDatum')
            ->orderBy('rennUhrzeit', 'desc')
            ->first();

        if (!$race) {
            return view('obsLive.emty');
        }

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

    public function bauchbinde()
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
            ->first();

        if ($race) {

            return view('obsLive.bauchbinde')->with(
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
            ->where(function($query) {
                $query->where('status', 2)
                    ->orWhere(function($query) {
                        $query->where('aktuellLiveVideo', 1);
                    });
            })
            ->orderBy('aktuellLiveVideo' , 'desc')
            ->orderBy('rennDatum')
            ->orderBy('rennUhrzeit')
            ->first();

        if ($race) {

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
