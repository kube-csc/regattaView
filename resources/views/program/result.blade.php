@extends('layouts.frontend')

@section('title' ,'Banbelegung')

@section('content')

    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container">
                <div class="section-title" data-aos="fade-in" data-aos-delay="50">
                    <h2>{{ $ueberschrift }}</h2>
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
                        <tr>
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
                            <a href="{{ url('/Ergebnis/'.$previousRace->id) }}" class="btn btn-primary">&larr; Zurück</a>
                        @endif
                        @if($nextRace)
                            <a href="{{ url('/Ergebnis/'.$nextRace->id) }}" class="btn btn-primary">Weiter &rarr;</a>
                        @endif
                    </p>
                </div>
            </div>
        </section><!-- End Services Section -->
    </main><!-- End #main -->

@endsection
