@extends('layouts.frontend')

@section('title' ,'Banbelegung '.$eventname)

@section('content')

    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container">
                <div class="section-title" data-aos="fade-in" data-aos-delay="50">
                    <h2>{{ $ueberschrift }}</h2>
                    {{-- Mannschaftsfilter Anzeige und Umschalter --}}
                    @php
                        $filterTeam = session('team_filter') ? \App\Models\RegattaTeam::with('teamWertungsGruppe')->find(session('team_filter')) : null;
                        $filterActive = session('team_filter_active', true);
                    @endphp
                    @if($filterTeam)
                        <span class="badge m-2"
                              style="background-color: #ff9800; color: #fff; font-size: 1em; padding: 0.4em 0.8em;">
                            Gefiltert nach: {{ $filterTeam->teamname }}
                            @if($filterTeam->teamWertungsGruppe && $filterTeam->teamWertungsGruppe->typ)
                                ({{ $filterTeam->teamWertungsGruppe->typ }})
                            @endif
                            @if(!$filterActive)
                                [Filter aus]
                            @endif
                        </span>
                        <form method="POST" action="{{ route('program.setTeamFilter') }}" class="ms-3 d-inline">
                            @csrf
                            <input type="hidden" name="toggle" value="1">
                            <input type="hidden" name="redirect" value="{{ url()->current() }}">
                            <button type="submit"
                                    class="btn {{ $filterActive ? 'btn-warning' : 'btn-success' }} btn-sm btn-primary rounded-lg m-2"
                                    style="font-size: 0.95em; padding: 0.3em 1em;">
                                Filter {{ $filterActive ? 'aus' : 'an' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('program.selectTeamFilter') }}" class="ms-3">
                            <button type="button" class="btn btn-success btn-sm btn-primary rounded-lg m-2"
                                    style="font-size: 0.95em; padding: 0.3em 1em;">
                                Filter an
                            </button>
                        </a>
                    @endif
                    <p>
                        <label for="name">Nummer:</label>
                        @if(is_numeric($race->nummer))
                            {{ $race->nummer }}. {{ $race->rennBezeichnung }}
                        @else
                            {{ $race->nummer }} / {{ $race->rennBezeichnung }}
                        @endif
                        <br>
                        @if($race->raceTabele->ueberschrift)
                            <label for="name">Tabelle:</label>
                            {{ $race->raceTabele->ueberschrift }}
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
                        @php
                            $to   = explode(":" , $race->rennUhrzeit);
                            $from = explode(":" , $race->verspaetungUhrzeit);
                            $timto=$to[0]*60+$to[1];
                            $timfrom=$from[0]*60+$from[1];
                            $diff_in_minutes=$timfrom-$timto;
                        @endphp
                        @if($diff_in_minutes>5 && ($race->programmDatei != Null && $race->ergebnisDatei == Null) || ($race->status == 2))
                            <br>Voraussichtlich: {{ date("H:i", strtotime($race->verspaetungUhrzeit)) }} Uhr
                        @endif
                    </p>
                    <br>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Bahn</th>
                            <th>Team</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($lanes as $lane)
                                <tr>
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
                           <a href="{{ url('/Bahnbelegung/'.$previousRace->id) }}"
                              class="btn btn-primary">
                               &larr; Zurück
                           </a>
                        @endif
                        @if($nextRace)
                           <a href="{{ url('/Bahnbelegung/'.$nextRace->id) }}"
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
