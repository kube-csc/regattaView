<?php

namespace App\Http\Controllers;

//use App\Models\Dokumente;
use App\Http\Requests\StoreDokumenteRequest;
use App\Http\Requests\UpdateDokumenteRequest;
use App\Models\Event;
use App\Models\Report;
use App\Models\Tabele;
use Illuminate\Support\Carbon;

class DokumenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::join('races as ra', 'events.id', '=', 'ra.event_id')
            ->where('events.regatta', '1')
            ->where('events.verwendung', 0)
            ->orderby('events.datumvon', 'desc')
            ->limit(1)
            ->get();

        // Es wird $event->event_id verwendet weil die id in events und races vorhanden wird und events->id mit races->id überschrieben
        $eventId = 0;
        foreach ($events as $event) {
            $eventId = $event->event_id;
        }

        $temp = 0;
        $eventDokumentes = Report::where('event_id', $eventId)
            ->where('visible', 1)
            ->where('webseite', 1)
            ->where('verwendung', '>', 1)
            ->where('verwendung', '<', 6)
            ->where('typ', '>', 9)
            ->where('typ', '<', 13)
            ->where(function ($query) use ($temp) {
                $query->where('bild', "!=", NULL)
                    ->orwhere('image', "!=", NULL);
            })
            ->orderby('verwendung')
            ->orderby('position')
            ->get();


        return view('dokumente.index')->with(
            [
                'eventDokumentes' => $eventDokumentes,
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
     * @param  \App\Http\Requests\StoreDokumenteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDokumenteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dokumente  $dokumente
     * @return \Illuminate\Http\Response
     */
    public function show(Dokumente $dokumente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dokumente  $dokumente
     * @return \Illuminate\Http\Response
     */
    public function edit(Dokumente $dokumente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDokumenteRequest  $request
     * @param  \App\Models\Dokumente  $dokumente
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDokumenteRequest $request, Dokumente $dokumente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dokumente  $dokumente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dokumente $dokumente)
    {
        //
    }
}
