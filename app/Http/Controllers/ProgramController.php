<?php

namespace App\Http\Controllers;

use App\Models\Lane;
use App\Models\Program;
use App\Models\Event;
use App\Models\Race;
use App\Models\RegattaTeam;
use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $events = $this->getCurrentEventsQuery()->get();

        // Es wird $event->event_id verwendet weil die id in events und races vorhanden wird und events->id mit races->id überschrieben
        $eventId=0;
        foreach($events as $event) {
           $eventId=$event->event_id;
        }

        $teamFilter = session('team_filter');
        $teamFilterActive = session('team_filter_active', true);

        $racesQuery = Race::where('event_id', $eventId)
            ->where('visible' , 1)
            ->orderby('rennDatum')
            ->orderby('rennUhrzeit');
        if ($teamFilter && $teamFilterActive) {
            $racesQuery->whereHas('lanes', function($q) use ($teamFilter) {
                $q->where('mannschaft_id', $teamFilter);
            });
        }
        $races = $racesQuery->get();

        return view('program.index')->with([
                'races'        => $races,
                'ueberschrift' => 'Programm von allen Rennen',
                'event'        => $events->first()->ueberschrift,
                'teamFilter'   => $teamFilter ? RegattaTeam::find($teamFilter) : null,
                'teamFilterActive' => $teamFilterActive,
            ]);
    }

    public function indexNotResult()
    {
        $events = $this->getCurrentEventsQuery()
            ->where('ra.visible', 1)   // ToDo: Events können noch nicht mit visible abgefragt werden
            ->get();

        // Es wird $event->event_id verwendet weil die id in events und races vorhanden wird und events->id mit races->id überschrieben
        $eventId=0;
        foreach($events as $event) {
            $eventId=$event->event_id;
        }

        $teamFilter = session('team_filter');
        $teamFilterActive = session('team_filter_active', true);

        $racesQuery = Race::where('event_id', $eventId)
            ->where('visible' , 1)
            ->where(function($query) {
                $query->where('programmDatei', '!=', Null)
                    ->orWhere('status', '2');
            })
            ->where('ergebnisDatei' , Null)
            ->where('rennDatum' , '>=' , Carbon::now()->toDateString())
            ->orderby('rennDatum')
            ->orderby('rennUhrzeit');
        if ($teamFilter && $teamFilterActive) {
            $racesQuery->whereHas('lanes', function($q) use ($teamFilter) {
                $q->where('mannschaft_id', $teamFilter);
            });
        }
        $races = $racesQuery->get();

        return view('program.index')->with(
            [
                'races'        => $races,
                'ueberschrift' => 'Programm von verlosten Rennen die noch nicht gestartet sind.',
                'event'        => $events->first()->ueberschrift,
                'teamFilter'   => $teamFilter ? RegattaTeam::find($teamFilter) : null,
                'teamFilterActive' => $teamFilterActive,
            ]);
    }

    public function indexResult()
    {
        $events = $this->getCurrentEventsQuery()
            ->where('ra.visible', 1)   // ToDo: Events koennen noch nicht mit visible abgefragt werden
            ->get();

        // Es wird $event->event_id verwendet weil die id in events und races vorhanden wird und events->id mit races->id überschrieben
        $eventId=0;
        foreach($events as $event) {
            $eventId=$event->event_id;
        }

        $teamFilter = session('team_filter');
        $teamFilterActive = session('team_filter_active', true);

        $racesQuery = Race::where('event_id', $eventId)
            ->where('visible' , 1)
            ->where(function($query) {
                $query->where('ergebnisDatei', '!=', Null)
                    ->orWhere('status', '4');
            })
            ->where(function($query) {
                $query->where('veroeffentlichungUhrzeit', '<', Carbon::now()->toTimeString())
                    ->orWhere('rennDatum', '<', Carbon::now()->toDateString());
            })
            ->orderby('rennDatum' , 'desc')
            ->orderby('rennUhrzeit' , 'desc');

        if ($teamFilter && $teamFilterActive) {
            $racesQuery->whereHas('lanes', function($q) use ($teamFilter) {
                $q->where('mannschaft_id', $teamFilter);
            });
        }
        $races = $racesQuery->get();

        return view('program.index')->with(
            [
                'races'        => $races,
                'ueberschrift' => 'Ergebnisse der Rennen',
                'event'        => $events->first()->ueberschrift,
                'teamFilter'   => $teamFilter ? RegattaTeam::find($teamFilter) : null,
                'teamFilterActive' => $teamFilterActive
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

    public function laneOccupancy($raceId)
    {
        $events = $this->getCurrentEventsQuery()
            ->where('ra.visible', 1)
            ->get();

        $eventId=0;
        foreach($events as $event) {
            $eventId=$event->event_id;
        }

        $race = Race::find($raceId);

        $lanes = Lane::where('rennen_id', $raceId)
            ->orderBy('bahn')
            ->get();

        $teamFilter = session('team_filter');
        $teamFilterActive = session('team_filter_active', true);

        // Filter nur anwenden, wenn auch aktiv
        if ($teamFilter && $teamFilterActive) {
            $previousRace = Race::where('event_id', $eventId)
                ->where('id', '!=', $raceId)
                ->where('status', 2)
                ->whereHas('lanes', function($q) use ($teamFilter) {
                    $q->where('mannschaft_id', $teamFilter);
                })
                ->where(function($query) use ($race) {
                    $query->where('rennDatum', '<', $race->rennDatum)
                          ->orWhere(function($q2) use ($race) {
                              $q2->where('rennDatum', $race->rennDatum)
                                 ->where('rennUhrzeit', '<', $race->rennUhrzeit);
                          });
                })
                ->orderBy('rennDatum', 'desc')
                ->orderBy('rennUhrzeit', 'desc')
                ->first();

            $nextRace = Race::where('event_id', $eventId)
                ->where('id', '!=', $raceId)
                ->where('status', 2)
                ->whereHas('lanes', function($q) use ($teamFilter) {
                    $q->where('mannschaft_id', $teamFilter);
                })
                ->where(function($query) use ($race) {
                    $query->where('rennDatum', '>', $race->rennDatum)
                          ->orWhere(function($q2) use ($race) {
                              $q2->where('rennDatum', $race->rennDatum)
                                 ->where('rennUhrzeit', '>', $race->rennUhrzeit);
                          });
                })
                ->orderBy('rennDatum', 'asc')
                ->orderBy('rennUhrzeit', 'asc')
                ->first();
        } else {
            // Standard: alle Rennen
            $previousRace = Race::where('event_id', $eventId)
                ->where('id', '!=', $raceId)
                ->where('status', 2)
                ->where(function($query) use ($race) {
                    $query->where('rennDatum', '<', $race->rennDatum)
                          ->orWhere(function($q2) use ($race) {
                              $q2->where('rennDatum', $race->rennDatum)
                                 ->where('rennUhrzeit', '<', $race->rennUhrzeit);
                          });
                })
                ->orderBy('rennDatum', 'desc')
                ->orderBy('rennUhrzeit', 'desc')
                ->first();

            $nextRace = Race::where('event_id', $eventId)
                ->where('id', '!=', $raceId)
                ->where('status',  2)
                ->where(function($query) use ($race) {
                    $query->where('rennDatum', '>', $race->rennDatum)
                          ->orWhere(function($q2) use ($race) {
                              $q2->where('rennDatum', $race->rennDatum)
                                 ->where('rennUhrzeit', '>', $race->rennUhrzeit);
                          });
                })
                ->orderBy('rennDatum', 'asc')
                ->orderBy('rennUhrzeit', 'asc')
                ->first();
        }

        return view('program.laneOccupancy')->with(
            [
                'race'         => $race,
                'lanes'        => $lanes,
                'previousRace' => $previousRace,
                'nextRace'     => $nextRace,
                'ueberschrift' => 'Bahnbelegung',
                'eventname'    => $events->first()->ueberschrift,
                'teamFilter'   => session('team_filter') ? RegattaTeam::find(session('team_filter')) : null
            ]);
    }

    public function result($raceId)
    {
        $events = $this->getCurrentEventsQuery()
            ->where('ra.visible', 1)
            ->get();

        $eventId=0;
        foreach($events as $event) {
            $eventId=$event->event_id;
        }

        $race = Race::find($raceId);

        $teamFilter = session('team_filter');
        $teamFilterActive = session('team_filter_active', true);

        $now = now();
        // Filter nur anwenden, wenn auch aktiv
        if ($teamFilter && $teamFilterActive) {
            $previousRace = Race::where('event_id', $eventId)
                ->where('id', '!=', $raceId)
                ->where('status',  4)
                ->whereHas('lanes', function($q) use ($teamFilter) {
                    $q->where('mannschaft_id', $teamFilter);
                })
                ->where(function($query) use ($race) {
                    $query->where('rennDatum', '<', $race->rennDatum)
                          ->orWhere(function($q2) use ($race) {
                              $q2->where('rennDatum', $race->rennDatum)
                                 ->where('rennUhrzeit', '<', $race->rennUhrzeit);
                          });
                })
                ->whereRaw("(CONCAT(rennDatum, ' ', veroeffentlichungUhrzeit) <= ?)", [$now->format('Y-m-d H:i:s')])
                ->orderBy('rennDatum', 'desc')
                ->orderBy('rennUhrzeit', 'desc')
                ->first();

            $nextRace = Race::where('event_id', $eventId)
                ->where('id', '!=', $raceId)
                ->where('status', 4)
                ->whereHas('lanes', function($q) use ($teamFilter) {
                    $q->where('mannschaft_id', $teamFilter);
                })
                ->where(function($query) use ($race) {
                    $query->where('rennDatum', '>', $race->rennDatum)
                          ->orWhere(function($q2) use ($race) {
                              $q2->where('rennDatum', $race->rennDatum)
                                 ->where('rennUhrzeit', '>', $race->rennUhrzeit);
                          });
                })
                ->whereRaw("(CONCAT(rennDatum, ' ', veroeffentlichungUhrzeit) <= ?)", [$now->format('Y-m-d H:i:s')])
                ->orderBy('rennDatum', 'asc')
                ->orderBy('rennUhrzeit', 'asc')
                ->first();
        } else {
            $previousRace = Race::where('event_id', $eventId)
                ->where('id', '!=', $raceId)
                ->where('status',  4)
                ->where(function($query) use ($race) {
                    $query->where('rennDatum', '<', $race->rennDatum)
                          ->orWhere(function($q2) use ($race) {
                              $q2->where('rennDatum', $race->rennDatum)
                                 ->where('rennUhrzeit', '<', $race->rennUhrzeit);
                          });
                })
                ->whereRaw("(CONCAT(rennDatum, ' ', veroeffentlichungUhrzeit) <= ?)", [$now->format('Y-m-d H:i:s')])
                ->orderBy('rennDatum', 'desc')
                ->orderBy('rennUhrzeit', 'desc')
                ->first();

            $nextRace = Race::where('event_id', $eventId)
                ->where('id', '!=', $raceId)
                ->where('status', 4)
                ->where(function($query) use ($race) {
                    $query->where('rennDatum', '>', $race->rennDatum)
                          ->orWhere(function($q2) use ($race) {
                              $q2->where('rennDatum', $race->rennDatum)
                                 ->where('rennUhrzeit', '>', $race->rennUhrzeit);
                          });
                })
                ->whereRaw("(CONCAT(rennDatum, ' ', veroeffentlichungUhrzeit) <= ?)", [$now->format('Y-m-d H:i:s')])
                ->orderBy('rennDatum', 'asc')
                ->orderBy('rennUhrzeit', 'asc')
                ->first();

        }

        $lanes = Lane::where('rennen_id', $raceId)
            ->where('platz', '!=', 0)
            ->orderBy('platz')
            ->get();

        return view('program.result')->with(
            [
                'race'               => $race,
                'previousRace' => $previousRace,
                'nextRace'       => $nextRace,
                'lanes'             => $lanes,
                'ueberschrift'  => 'Bahnbelegung',
                'eventname'    => $events->first()->ueberschrift,
                'teamFilter'      => session('team_filter') ? RegattaTeam::find(session('team_filter')) : null
            ]);
    }

    // Neue Seite zur Auswahl der Mannschaft als Filter
    public function selectTeamFilter()
    {
        $events = $this->getCurrentEventsQuery()->get();

        $eventId = 0;
        foreach($events as $event) {
           $eventId        = $event->event_id;
        }

        // Nur Teams, die in Rennen des aktuellen Events gemeldet sind
        $teams = RegattaTeam::where('regatta_id', $eventId)
            ->where('status', '!=','Gelöscht')
            ->orderBy('teamname')
            ->get();

        if ($teams->isEmpty()) {
            Session::put('team_filter_possible', false);
            return redirect()->back()->with('error', 'Es sind keine Mannschaften gemeldet oder keine Rennen mit Mannschaften vorhanden.');
        } else {
            Session::put('team_filter_possible', true);
        }
        $currentFilter = session('team_filter');
        return view('program.selectTeamFilter', compact('teams', 'currentFilter'));
    }

    // Setzt oder toggelt den Filter in der Session (Toggle)
    public function setTeamFilter(Request $request)
    {
        $request->validate(['team_id' => 'nullable|exists:regatta_teams,id']);
        $current = session('team_filter');
        $active = session('team_filter_active', true);

        if ($request->has('toggle')) {
            // Toggle-Button gedrückt
            if ($active) {
                Session::put('team_filter_active', false);
            } else {
                Session::put('team_filter_active', true);
            }
        } elseif ($request->team_id) {
            // Auswahl einer Mannschaft
            Session::put('team_filter', $request->team_id);
            Session::put('team_filter_active', true);
        } else {
            // Kein Filter gewählt
            Session::forget('team_filter');
            Session::forget('team_filter_active');
        }

        // Nach dem Umschalten auf die gewünschte Seite zurückkehren
        if ($request->filled('redirect')) {
            $redirectUrl = $request->input('redirect');
            // Prüfe, ob wir auf einer Ergebnis-Seite sind und Filter wurde aktiviert
            if (str_contains($redirectUrl, '/Ergebnis/') && session('team_filter_active')) {
                $raceId = null;
                if (preg_match('~/Ergebnis/(\d+)~', $redirectUrl, $match)) {
                    $raceId = $match[1];
                }
                $teamFilter = session('team_filter');
                if ($raceId && $teamFilter) {
                    $race = Race::find($raceId);
                    if ($race) {
                        // Suche das nächste Ergebnisrennen mit diesem Team
                        $nextRace = Race::where('event_id', $race->event_id)
                            ->where('visible', 1)
                            ->where('status', 4)
                            ->whereHas('lanes', function($q) use ($teamFilter) {
                                $q->where('mannschaft_id', $teamFilter);
                            })
                            ->where(function($query) use ($race) {
                                $query->where('rennDatum', '>', $race->rennDatum)
                                      ->orWhere(function($q2) use ($race) {
                                          $q2->where('rennDatum', $race->rennDatum)
                                             ->where('rennUhrzeit', '>=', $race->rennUhrzeit);
                                      });
                            })
                            ->orderBy('rennDatum', 'asc')
                            ->orderBy('rennUhrzeit', 'asc')
                            ->first();

                        // Falls kein zukünftiges, dann das erste überhaupt
                        if (!$nextRace) {
                            $nextRace = Race::where('event_id', $race->event_id)
                                ->where('visible', 1)
                                ->where('status', 4)
                                ->whereHas('lanes', function($q) use ($teamFilter) {
                                    $q->where('mannschaft_id', $teamFilter);
                                })
                                ->orderBy('rennDatum', 'asc')
                                ->orderBy('rennUhrzeit', 'asc')
                                ->first();
                        }

                        if ($nextRace) {
                            return redirect('/Ergebnis/' . $nextRace->id);
                        }
                    }
                }
            }
            // Prüfe, ob wir auf einer Bahnbelegung-Seite sind und Filter wurde aktiviert
            if (str_contains($redirectUrl, '/Bahnbelegung/') && session('team_filter_active')) {
                $raceId = null;
                if (preg_match('~/Bahnbelegung/(\d+)~', $redirectUrl, $match)) {
                    $raceId = $match[1];
                }
                $teamFilter = session('team_filter');
                if ($raceId && $teamFilter) {
                    $race = Race::find($raceId);
                    if ($race) {
                        // Suche das nächste Bahnbelegungsrennen mit diesem Team
                        $nextRace = Race::where('event_id', $race->event_id)
                            ->where('visible', 1)
                            ->where('status', '>=', 2)
                            ->where('status', '<=', 4)
                            ->whereHas('lanes', function($q) use ($teamFilter) {
                                $q->where('mannschaft_id', $teamFilter);
                            })
                            ->where(function($query) use ($race) {
                                $query->where('rennDatum', '>', $race->rennDatum)
                                      ->orWhere(function($q2) use ($race) {
                                          $q2->where('rennDatum', $race->rennDatum)
                                             ->where('rennUhrzeit', '>=', $race->rennUhrzeit);
                                      });
                            })
                            ->orderBy('rennDatum', 'asc')
                            ->orderBy('rennUhrzeit', 'asc')
                            ->first();

                        // Falls kein zukünftiges, dann das erste überhaupt
                        if (!$nextRace) {
                            $nextRace = \App\Models\Race::where('event_id', $race->event_id)
                                ->where('visible', 1)
                                ->where('status', '>=', 2)
                                ->where('status', '<=', 4)
                                ->whereHas('lanes', function($q) use ($teamFilter) {
                                    $q->where('mannschaft_id', $teamFilter);
                                })
                                ->orderBy('rennDatum', 'asc')
                                ->orderBy('rennUhrzeit', 'asc')
                                ->first();
                        }

                        if ($nextRace) {
                            return redirect('/Bahnbelegung/' . $nextRace->id);
                        }
                    }
                }
            }
            return redirect($redirectUrl);
        }
        return redirect()->route('program.index');
    }

    /**
     * Gibt die aktuelle Event-Query zurück.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getCurrentEventsQuery()
    {
        return Event::join('races as ra', 'events.id', '=', 'ra.event_id')
            ->where('ra..status' , '>',1)
            ->where('events.regatta', '1')
            ->where('events.verwendung', 0)
            ->orderby('events.datumvon', 'desc')
            ->limit(1);
    }
}
