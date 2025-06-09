@extends('layouts.speeker')

@section('title' ,'Sprecher Teaminformation '.$event->ueberschrift)

@section('content')

    <main id="main">
        <!-- ======= Search Section ======= -->
        <section id="search" class="search">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="box">
                            <form action="/Sprecher/Mannschaft/Auswahl" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="raceId" name="raceId" value="{{ $raceId }}">
                                <div class="search-box d-flex">
                                    <select name="teamId" id="raceId" class="form-control me-2">
                                        @foreach($teamsChoose as $teamChoose)
                                            <option value="{{ $teamChoose->id }}" @selected($teamChoose->id == $teamId)>
                                                {{ $teamChoose->teamname }}
                                                @if($teamChoose->beschreibung)
                                                    (Info)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-secondary me-2 ml-2">auswahl</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box">
                            <a href="/Sprecher/Mannschaft/{{ $teamId }}/{{ $raceId }}" class="me-2">
                                <button type="button" class="btn btn-secondary ml-2 px-4">
                                    <i class="bx bx-refresh"></i>
                                </button>
                            </a>
                            <a href="/Sprecher" class="me-2">
                                <button type="button" class="btn btn-secondary ml-2 px-4">
                                    <i class="bx bx-time"></i>
                                </button>
                            </a>
                            <a href="/Sprecher/{{ $raceId }}">
                                <button type="button" class="btn btn-secondary ml-2 px-4">Programm</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ======= Services Section ======= -->
        <section id="about" class="about">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="box">
                            <!-- Content for the first box -->
                            <h2>{{ $team->teamname }}</h2>
                            <p>
                                {!! $team->beschreibung !!}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box">
                            <!-- Content for the second box -->
                            @if($lanes!=Null)
                                @php
                                    $bahn=0;
                                @endphp
                                @if(is_numeric($race->nummer))
                                    <h2>{{ $race->nummer }}. {{ $race->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $race->nummer }} / {{ $race->rennBezeichnung }}</h2>
                                @endif
                                @if($race->status < 2)
                                <p>Rennen noch nicht gesetzt</p>
                                @endif
                                @if($race->status == 2 or ($victoCremony == 0 and $race->status == 4))
                                    @if($race->status >= 3)
                                        <p>
                                           Ergebnis wird auf der Siegerehrung bekannt gegeben.
                                        </p>
                                    @endif
                                    <p>
                                        @foreach($lanes as $lane)
                                            @php
                                                $bahn++;
                                            @endphp
                                            <label for="name">Bahn:</label>
                                            {{ $bahn }}
                                            @if($lane->mannschaft_id != null)
                                                @include('components.teamButton', [
                                                    'beschreibung' => $lane->regattaTeam->beschreibung,
                                                    'id' => $lane->mannschaft_id,
                                                    'raceId' => $race->id,
                                                    'teamname' => $lane->regattaTeam->teamname
                                                ])
                                            @endif
                                            <br>
                                        @endforeach
                                    </p>
                                    @if($race->beschreibung)
                                        <hr></hr>
                                        <h3>Beschreibung zum Rennen</h3>
                                        <p>
                                            {!! $race->beschreibung !!}
                                        </p>
                                    @endif
                                @endif
                                @if($race->status == 4)
                                    @if($victoCremony == 1)
                                        @php
                                          $platz=0 ;
                                        @endphp
                                        <p>
                                            @foreach($lanes as $lane)
                                                @php
                                                    $platz++;
                                                @endphp
                                                <label for="name">Platz:</label>
                                                {{ $platz }}
                                                <label for="name">Bahn:</label>
                                                {{ $lane->bahn }}
                                                @if($lane->mannschaft_id!=Null)
                                                    @if($lane->regattaTeam->beschreibung != Null)
                                                        <a href="/Sprecher/Mannschaft/{{ $lane->mannschaft_id }}/{{ $race->id }}" class="me-2">
                                                            <button type="button" class="btn btn-secondary ml-2">{{ $lane->regattaTeam->teamname }}</button>
                                                        </a>
                                                    @else
                                                        {{ $lane->regattaTeam->teamname }}
                                                    @endif
                                                @endif
                                                <br>
                                            @endforeach
                                        </p>
                                        @if($race->raceTabele->ueberschrift != Null && $race->raceTabele->tabelleVisible == 1)
                                            <hr />
                                            <div class="my-4">
                                                @if($victoCremonyTable == 1)
                                                    <h2>
                                                        <a href="/Sprecher/Tabelle/{{ $race->raceTabele->id }}/{{ $race->id }}" class="me-2">
                                                            <button type="button" class="btn btn-primary ml-2">Tabelle</button>
                                                        </a>
                                                        {{ $race->raceTabele->ueberschrift }}
                                                    </h2>
                                                @else
                                                    <h2>Tabelle - {{ $race->raceTabele->ueberschrift }}</h2>

                                                    Das Ergebnis wird auf der Siegerehrung bekannt gegeben.
                                                @endif
                                                <p>
                                                    @if($tabeledatas && $victoCremonyTable == 1)
                                                        @foreach($tabeledatas as $tabeledata)
                                                            Platz:
                                                            {{ $tabeledata->platz }} {{ $tabeledata->getMannschaft->teamname }}
                                                            <br>
                                                            {{ $tabeledata->punkte }} Punkt(e) / {{ $tabeledata->rennanzahl }} von {{ $race->raceTabele->maxrennen }} Rennen
                                                            @if($race->raceTabele->buchholzwertungaktiv)
                                                                / {{ $tabeledata->buchholzzahl }} Buchholzzahl
                                                            @endif
                                                            <br>
                                                        @endforeach
                                                        @if($race->raceTabele->fileTabelleDatei != Null)
                                                            <hr />
                                                            <a href="{{env('VEREIN_URL')}}/storage/tabeleDokumente/{{ $race->raceTabele->tabelleDatei }}" target="_blank">
                                                                <i class="bx bxs-file-doc"></i>Tabellen Dokument
                                                            </a>
                                                            <br>
                                                            Die Datei wurde am
                                                            {{ date("d.m.y", strtotime($race->raceTabele->updated_at)) }} {{ date("H:i", strtotime($race->raceTabele->updated_at)) }} Uhr
                                                            aktualisiert.
                                                        @endif
                                                    @endif
                                                    </p>
                                            </div>
                                        @endif
                                        @if($race->ergebnisBeschreibung)
                                            <hr></hr>
                                            <h3>Beschreibung zum Ergebnis</h3>
                                            <p>
                                                {!! $race->ergebnisBeschreibung !!}
                                            </p>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
