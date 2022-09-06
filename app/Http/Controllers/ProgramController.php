<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Event;
use App\Models\Race;
use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use Illuminate\Support\Carbon;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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

        $races = Race::where('event_id', $eventId)
            ->where('visible' , 1)
            ->orderby('rennDatum')
            ->orderby('rennUhrzeit')
            ->get();

        return view('program.index')->with(
            [
                'races'        => $races,
                'ueberschrift' => 'Programm von allen Rennen'
            ]);
    }

    public function indexNotResult()
    {
        $events = Event::join('races as ra' , 'events.id' , '=' , 'ra.event_id')
            ->where('ra.visible' , 1)   // ToDo: Events koennen noch nit mit visible abgefragt werden
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
            ->where('programmDatei' , '!=' , Null)
            ->where('ergebnisDatei' , Null)
            ->where('rennDatum' , '>=' , Carbon::now()->toDateString())
            ->orderby('rennDatum')
            ->orderby('rennUhrzeit')
            ->get();

        return view('program.index')->with(
            [
                'races'        => $races,
                'ueberschrift' => 'Programm von verlosten Rennen die noch nicht gestartet sind.'
            ]);
    }

    public function indexResult()
    {
        $events = Event::join('races as ra' , 'events.id' , '=' , 'ra.event_id')
            ->where('ra.visible' , 1)   // ToDo: Events koennen noch nit mit visible abgefragt werden
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
            ->where('ergebnisDatei' , '!=' , Null)
            ->orderby('rennDatum' , 'desc')
            ->orderby('rennUhrzeit' , 'desc')
            ->get();

        return view('program.index')->with(
            [
                'races'        => $races,
                'ueberschrift' => 'Ergebnisse der Rennen'
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
     * @param  \App\Http\Requests\StoreProgramRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProgramRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function show(Program $program)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function edit(Program $program)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProgramRequest  $request
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProgramRequest $request, Program $program)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        //
    }
}
