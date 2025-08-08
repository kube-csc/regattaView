@extends('layouts.frontend')

@section('title' ,'Banbelegung '.$eventname)

@section('content')

    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container">
                <div class="section-title" data-aos="fade-in" data-aos-delay="50">
                    <h2>{{ $ueberschrift }}</h2>
                    {{-- Mannschaftsfilter Anzeige und Toggle --}}
                    <div class="d-flex flex-column align-items-center mb-2">
                        @php
                            $filterTeam = null;
                            if(session('team_filter')) {
                                $filterTeam = \App\Models\RegattaTeam::with('teamWertungsGruppe')->find(session('team_filter'));
                            }
                            $filterActive = isset($teamFilterActive) ? $teamFilterActive : session('team_filter_active', true);
                            $filterPossible = session('team_filter_possible', true);
                        @endphp
                        @if($filterTeam)
                            <div class="mb-1" style="font-size:0.8em;">
                                <span class="badge m-1"
                                      style="background-color: #ff9800; color: #fff; font-size: 0.85em; padding: 0.2em 0.6em;">
                                    Gefiltert nach: {{ $filterTeam->teamname }}
                                    @if(!$filterActive)
                                        [Filter aus]
                                    @endif
                                </span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-center align-items-center mb-2 flex-wrap">
                            @if($filterPossible)
                                <a href="{{ route('program.selectTeamFilter') }}" class="me-2 mb-1">
                                    <button type="button" class="btn btn-secondary rounded-lg m-1 btn-sm">
                                        Team filtern
                                    </button>
                                </a>
                                @if($filterTeam)
                                    <form method="POST" action="{{ route('program.setTeamFilter') }}" class="mb-1">
                                        @csrf
                                        <input type="hidden" name="toggle" value="1">
                                        <input type="hidden" name="redirect" value="{{ url()->current() }}">
                                        <button type="submit"
                                                class="btn {{ $filterActive ? 'btn-warning' : 'btn-success' }} btn-sm rounded-lg m-1"
                                                style="font-size: 0.95em; padding: 0.3em 1em;">
                                            Filter {{ $filterActive ? 'aus' : 'an' }}
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                    <p>
                        <label for="name">Nummer:</label>
                        @if(is_numeric($race->nummer))
                            {{ $race->nummer }}. {{ $race->rennBezeichnung }}
                        @else
                            {{ $race->nummer }} / {{ $race->rennBezeichnung }}
                        @endif
                        <br>
                        @if($race->raceTabele->ueberschrift)
                            @if($race->tabele_id && $race->tabele_id >1)
                                <a href="{{ url('/Tabelle/'.$race->tabele_id) }}" class="btn btn-primary">
                                    Tabelle {{ $race->raceTabele->ueberschrift }}
                                </a>
                                <br>
                            @endif
                            <label for="name">Download zur Tabelle:</label>
                            @if($race->raceTabele->tabelleDatei != Null)
                                <p><a href="{{ env('VEREIN_URL')}}/storage/tabeleDokumente/{{ $race->raceTabele->tabelleDatei }}" target="_blank">
                                        <i class="bx bxs-file-doc"></i>
                                        {{ $race->raceTabele->ueberschrift }}
                                    </a>
                                </p>
                            @else
                            {{ $race->raceTabele->ueberschrift }}
                            @endif
                            @if($race->mix==1)
                               <br>Mix Rennen
                            @endif
                        @endif
                        @php
                            $rennUhrzeitAlt= substr($race->rennUhrzeit, 0, -3);
                        @endphp
                        <br>
                        <label for="name">Startzeit:</label>
                        {{ $rennUhrzeitAlt }} Uhr {{ \Carbon\Carbon::parse($race->rennDatum)->format('d.m.Y') }}
                    </p>
                    <table class="table table-striped">
                        <thead>
                        <tr style="background-color: #e3f0ff;">
                            <th>Platz</th>
                            <th>Bahn</th>
                            <th>Team</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $platz = 0
                        @endphp
                        @foreach($lanes as $lane)
                            @php
                                $platz++
                            @endphp
                            <tr>
                                <td>{{ $platz }}</td>
                                <td>{{ $lane->bahn }}</td>
                                <td>
                                    @if($lane->mannschaft_id)
                                        {{ $lane->regattaTeam->teamname }}
                                    @else
                                        Frei
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <p>
                        @if($previousRace)
                            <a href="{{ url('/Ergebnis/'.$previousRace->id) }}"
                               class="btn btn-primary">
                                &larr; Zurück
                            </a>
                        @endif
                        @if($nextRace)
                            <a href="{{ url('/Ergebnis/'.$nextRace->id) }}"
                               class="btn btn-primary">
                                Weiter &rarr;
                            </a>
                        @endif
                    </p>
                </div>
            </div>
        </section><!-- End Services Section -->
    </main><!-- End #main -->

@endsection
