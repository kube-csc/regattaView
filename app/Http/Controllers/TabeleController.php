<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Tabele;
use App\Http\Requests\StoreTabeleRequest;
use App\Http\Requests\UpdateTabeleRequest;
use App\Models\Tabledata;
use Illuminate\Support\Carbon;

class TabeleController extends Controller
{
    public function __construct()
    {
        $this->currentDate = Carbon::now()->toDateString();
        $this->currentTime = Carbon::now()->toTimeString();
        //Temp: Testdaten
        $this->currentDate = "2023-08-26"; // For testing purposes, set a fixed date
        $this->currentTime = "20:30:00"; // For testing purposes, set a fixed time
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function index()
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

        $tabeles = Tabele::where('event_id', $eventId)
            ->where('tabelleVisible' , 1)
//            ->where(function($query) {
//                $query->where('finaleAnzeigen', '<', Carbon::now()->toTimeString())
//                    ->orWhere('tabelleDatumVon', '<', Carbon::now()->toDateString());
//            })
            ->where(function($query) {
                $query->where([
                    ['finaleAnzeigen', '<', $this->currentTime],
                    ['tabelleDatumVon', '=', $this->currentDate]
                ])
                    ->orWhere('tabelledatumVon', '<', $this->currentDate);
            })
//            ->where(function ($query) use ($eventId) {
//                $query->where('tabelleDatei' , '!=' , Null)
//                      ->orwhere('beschreibung' , '!=' , NULL);
//            })
            ->orderby('tabelleDatumVon')
            ->orderby('tabelleLevelBis')
            ->orderby('tabelleLevelVon')
            ->get();

        return view('table.index')->with(
            [
                'tabeles'         => $tabeles,
                'ueberschrift'    => 'Tabellen'
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTabeleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTabeleRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tabele  $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function show($tableId)
    {
        $victoCremonyTableShow=1;
        $tableShow = Tabele::find($tableId);
        if($tableShow && $tableShow->tabelleVisible == 1) {
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

        return view('table.show')->with(
            [
                'tableShow'             => $tableShow,
                'tableId'               => $tableId,
                'victoCremonyTableShow' => $victoCremonyTableShow,
                'tabeledataShows'       => $tabeledataShows,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tabele  $table
     * @return \Illuminate\Http\Response
     */
    public function edit(Tabele $table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTabeleRequest  $request
     * @param  \App\Models\Tabele  $table
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTabeleRequest $request, Tabele $table)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tabele  $table
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tabele $table)
    {
        //
    }
}
