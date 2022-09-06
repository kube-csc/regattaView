<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\board;
use App\Models\RegattaInformation;
use App\Models\Report;
use App\Models\SportSection;
use App\Models\Event;
use App\Models\Race;
use App\Models\instruction;
use App\Models\Document;
use Illuminate\Support\Carbon;

class HomeController extends Controller
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

        $eventId = 0;
        $sportSectionId = 0;

        // Es wird $event->event_id verwendet weil die id in events und races vorhanden ist und events->id mit races->id überschrieben
        foreach($events as $event) {
            $sportSectionId = $event->sportSection_id;
            $eventId        = $event->event_id;
        }

        $temp=0;
        $regattaInformations = RegattaInformation::where('event_id' , $eventId)
            ->where(function ($query) use ($temp) {
                $query->where('startDatumVerschoben' , "<=" , Carbon::now())
                    ->orwhere('startDatumAktiv' , 0);
            })
            ->where(function ($query) use ($temp) {
                $query->where('endDatumVerschoben' , ">=" , Carbon::now())
                    ->orwhere('endDatumAktiv' , 0);
            })
            ->where('visible' , 1)
            ->orderby('position')
            ->get();

        $raceCount = Race::where('event_id', $eventId)
            ->where('visible' , 1)
            ->count();

        $raceNewCount = Race::where('event_id', $eventId)
            ->where('visible' , 1)
            ->where('programmDatei' , Null)
            ->where('ergebnisDatei' , Null)
            ->count();

        $raceProgrammCount = Race::where('event_id', $eventId)
            ->where('visible' , 1)
            ->where('programmDatei' , '!=' , Null)
            ->where('ergebnisDatei' , Null)
            ->count();

        $raceResoultCount = Race::where('event_id', $eventId)
            ->where('visible' , 1)
            ->where('ergebnisDatei' , '!=' , Null)
            ->count();

        $temp=0;
        $eventDokumentes = Report::where('event_id' , $eventId)
            ->where('visible' , 1)
            ->where('webseite' , 1)
            ->where('verwendung' , '>' , 1)
            ->where('verwendung' , '<' , 6)
            ->where('typ' , '>' , 9)
            ->where('typ' , '<' , 13)
            ->where(function ($query) use ($temp) {
                $query->where('bild'  , "!=" , NULL)
                    ->orwhere('image' , "!=" , NULL);
            })
            ->orderby('verwendung')
            ->orderby('position')
            ->get();

        $abteilungHomes = SportSection::where('id' , $sportSectionId)
            ->orderby('status')
            ->get();

        $sportSectionTeamName="";
        foreach ($abteilungHomes as $abteilungHome) {
            $sportSectionTeamName= $abteilungHome->abteilungTeamBezeichnung;
        }

        $boards=board::where('sportSection_id' , $sportSectionId)
            ->join('board_users as bu' , 'bu.board_id' , '=' , 'boards.id')
            ->join('users as us' , 'bu.boardUser_id' , '=' , 'us.id')
            ->leftjoin('board_portraits as bp' , 'bu.boardUser_id' , '=' , 'bp.postenUser_id')
            ->where('boards.visible' , 1)
            ->where('bu.visible' , 1)
            ->orderby('boards.position')
            ->orderby('bu.position')
            ->get();

        return view('home.home')->with(
            [
                'boards'                      => $boards,
                'sportSectionTeamName'        => $sportSectionTeamName,
                'events'                      => $events,
                'racecount'                   => $raceCount,
                'raceNewCount'                => $raceNewCount,
                'raceProgrammCount'           => $raceProgrammCount,
                'raceResoultCount'            => $raceResoultCount,
                'eventDokumentes'             => $eventDokumentes,
                'regattaInformations'         => $regattaInformations
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Home  $home
     * @return \Illuminate\Http\Response
     */
    public function show(Home $home)
    {
        //
    }

    public function imprint()
    {
        return view('home.imprint');
    }

    public function instructionShow($instructionSearch)
    {
        $search = str_replace('_' , ' ' , $instructionSearch);
        $instructions = instruction::where('ueberschrift' , $search)->get();

        foreach($instructions as $instruction){
            $instructionId = $instruction->id;
        }

        $documents = Document::where('instruction_id' , $instructionId)
            ->where('startDatum' , '<=' , Carbon::now()->toDateString())
            ->where('endDatum'   , '>=' , Carbon::now()->toDateString())
            ->where('dokumentenFile' ,'!=' , NULL)
            ->get();

        return view('instruction.show')->with([
            'documents'                   => $documents,
            'instructions'                => $instructions
        ]);
    }
}
