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
        $events = Event::where('datumbis' , '>=' , Carbon::now()->toDateString())
            ->where('regatta' , '1')
            ->where('verwendung' , 0)
            ->orderby('datumvon')
            ->limit(1)
            ->get();

        foreach($events as $event) {
            $races = Race::where('event_id', $event->id)
                ->orderby('datumvon')
                ->orderby('uhrzeit')
                ->get();
        }
        return view('program.index')->with(
            [
                'races'        => $races,
                'ueberschrift' => 'Programm von allen Rennen'
            ]);
    }

    public function indexNotResult()
    {
        $events = Event::where('datumbis' , '>=' , Carbon::now()->toDateString())
            ->where('regatta' , '1')
            ->where('verwendung' , 0)
            ->orderby('datumvon')
            ->limit(1)
            ->get();

        foreach($events as $event) {
            $races = Race::where('event_id', $event->id)
                ->where('programmDatei' , '!=' , Null)
                ->where('ergebnisDatei' , Null)
                ->where('datumvon' , '>=' , Carbon::now()->toDateString())
                ->orderby('datumvon')
                ->orderby('uhrzeit')
                ->get();
        }
        return view('program.index')->with(
            [
                'races'        => $races,
                'ueberschrift' => 'Programm von verlosten Rennen die noch nicht gestartet sind.'
            ]);
    }

    public function indexResult()
    {
        $events = Event::where('datumbis' , '>=' , Carbon::now()->toDateString())
            ->where('regatta' , '1')
            ->where('verwendung' , 0)
            ->orderby('datumvon')
            ->limit(1)
            ->get();

        foreach($events as $event) {
            $races = Race::where('event_id', $event->id)
                ->where('ergebnisDatei' , '!=' , Null)
                ->orderby('datumvon' , 'desc')
                ->orderby('uhrzeit' , 'desc')
                ->get();
        }
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
