@extends('layouts.speeker')

@section('title' ,'Sprecher Rennplan')

@section('content')

    <main id="main">
        <!-- ======= Search Section ======= -->
        <section id="search" class="search">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="box">
                            <form action="/Sprecher/Auswahl" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="search-box d-flex">
                                    <select name="speekerId" id="speekerId" class="form-control me-2">
                                        @foreach($racesChoose as $race)
                                            <option value="{{ $race->id }}">
                                                @if(is_numeric($race->nummer))
                                                    <h2>{{ $race->nummer }}. {{ $race->rennBezeichnung }}</h2>
                                                @else
                                                    <h2>{{ $race->nummer }} / {{ $race->rennBezeichnung }}</h2>
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
                            @if($raceNext1!= Null)
                                <a href="/Sprecher/{{ $raceNext1->id  }}" class="me-2">
                                    <button type="button" class="btn btn-secondary ml-2 px-4">aktualisieren</button>
                                </a>
                            @elseif($raceResoult1->id != Null)
                                <a href="/Sprecher/{{ $raceResoult1->id }}" class="me-2">
                                    <button type="button" class="btn btn-secondary ml-2 px-4">aktualisieren</button>
                                </a>
                            @endif
                                <a href="/Sprecher" class="me-2">
                                    <button type="button" class="btn btn-secondary ml-2 px-4">Aktuell</button>
                                </a>
                            @if($nachId>0)
                                <a href="/Sprecher/{{ $nachId }}" class="me-2">
                                    <button type="button" class="btn btn-secondary ml-2 px-4">Zurück</button>
                                </a>
                            @else
                                <button type="button" class="btn btn-secondary ml-2 px-4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                            @endif
                            @if($vorId>0)
                                <a href="/Sprecher/{{ $vorId }}">
                                    <button type="button" class="btn btn-secondary ml-2 px-4">Weiter</button>
                                </a>
                            @else
                                <button type="button" class="btn btn-secondary ml-2 px-4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                            @endif
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
                            @if($raceNext1    == Null && $raceResoult1  == Null)
                                <p>Es sind keine Rennen vorhanden.</p>
                            @endif
                            @if($raceNext1    != Null && $raceResoult1  == Null)
                                @php
                                    $bahn=0;
                                @endphp
                                @if(is_numeric($raceNext1->nummer))
                                    <h2>{{ $raceNext1->nummer }}. {{ $raceNext1->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $raceNext1->nummer }} / {{ $raceNext1->rennBezeichnung }}</h2>
                                @endif
                                <p>
                                    @if($raceNext1->status < 2)
                                        <br>Rennen noch nicht gesetzt<br><br>
                                    @else
                                    @foreach($lanesNext1 as $lane)
                                        @php
                                            $bahn++;
                                        @endphp
                                        <label for="name">Bahn:</label>
                                        {{ $bahn}}
                                        @if($lane->mannschaft_id!=Null)
                                            {{ $lane->regattaTeam->teamname }}
                                            @if($lane->regattaTeam->beschreibung != Null)
                                                <a href="/Sprecher/Mannschaft/{{ $lane->mannschaft_id }}/{{ $raceNext1->id }}" class="me-2">
                                                    <button type="button" class="btn btn-secondary ml-2">Info</button>
                                                </a>
                                            @endif
                                        @else
                                            frei
                                        @endif
                                        <br>
                                    @endforeach
                                    @endif
                                    @php
                                        $to   = explode(":" , $raceNext1->rennUhrzeit);
                                        $from = explode(":" , $raceNext1->verspaetungUhrzeit);
                                        $timto=$to[0]*60+$to[1];
                                        $timfrom=$from[0]*60+$from[1];
                                        $diff_in_minutes=$timfrom-$timto;
                                    @endphp
                                    Startzeit: {{ date("H:i", strtotime($raceNext1->rennUhrzeit)) }} Uhr
                                    @if($diff_in_minutes>5 && ($raceNext1->programmDatei != Null && $raceNext1->ergebnisDatei == Null) || ($raceNext1->status <= 2))
                                        <br>Voraussichtlich: {{ date("H:i", strtotime($raceNext1->verspaetungUhrzeit)) }} Uhr
                                    @endif
                                </p>
                                @if($raceNext1->beschreibung)
                                    <hr></hr>
                                    <h3>Beschreibung zum Rennen</h3>
                                    <p>
                                        {!! $raceNext1->beschreibung !!}
                                    </p>
                                @endif
                            @endif
                            @if($raceResoult1 != Null && $victoCremony1 == 0)
                                @if(is_numeric($raceResoult1->nummer))
                                    <h2>{{ $raceResoult1->nummer }}. {{ $raceResoult1->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $raceResoult1->nummer }} / {{ $raceResoult1->rennBezeichnung }}</h2>
                                @endif
                                <p>
                                @php
                                    $platz=0
                                @endphp
                                    @foreach($lanesResoult1 as $lane)
                                        @php
                                            $platz++
                                        @endphp
                                        <label for="name">Platz:</label>
                                        {{ $platz }}
                                        @if($lane->mannschaft_id!=Null)
                                            {{ $lane->regattaTeam->teamname }}
                                            @if($lane->regattaTeam->beschreibung != Null)
                                                <a href="/Sprecher/Mannschaft/{{ $lane->mannschaft_id }}/{{ $raceResoult1->id }}" class="me-2">
                                                    <button type="button" class="btn btn-secondary ml-2">Info</button>
                                                </a>
                                            @endif
                                        @endif
                                        <br>
                                    @endforeach
                                </p>
                                @if($raceResoult1->raceTabele->ueberschrift != Null)
                                    <hr class="my-4">
                                    <h3>Tabelle</h3>
                                    <p>
                                        {{ $raceResoult1->raceTabele->ueberschrift }}<br>
                                        @if($raceResoult1->raceTabele->fileTabelleDatei != Null)
                                            <a href="{{env('VEREIN_URL')}}/storage/tabeleDokumente/{{ $raceResoult1->raceTabele->fileTabelleDatei }}" target="_blank">
                                                    <i class="bx bxs-file-doc"></i>Tabellen Dokument
                                            </a>
                                            <br>
                                        @endif
                                        Akuallisiert:
                                        {{ date("d.m.y", strtotime($raceResoult1->raceTabele->updated_at)) }} {{ date("H:i", strtotime($raceResoult1->raceTabele->updated_at)) }} Uhr
                                    </p>
                                @endif
                                @if($raceResoult1->ergebnisBeschreibung)
                                    <hr></hr>
                                    <h3>Beschreibung zum Ergebnis</h3>
                                    <p>
                                        {!! $raceResoult1->ergebnisBeschreibung !!}
                                    </p>
                                @endif
                            @endif
                            @if($raceNext1    != Null && $raceResoult1  != Null && $victoCremony1 == 1)
                                @php
                                    $bahn=0;
                                @endphp
                                @if(is_numeric($raceNext1->nummer))
                                    <h2>{{ $raceNext1->nummer }}. {{ $raceNext1->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $raceNext1->nummer }} / {{ $raceNext1->rennBezeichnung }}</h2>
                                @endif
                                @if($raceNext1->status < 2)
                                    <p>Rennen noch nicht gesetzt</p>
                                @else
                                    <p>
                                        Ergebnis wird auf der Siegerehrung bekannt gegeben.
                                    </p>
                                    <hr></hr>
                                    <p>
                                        @foreach($lanesNext1 as $lane)
                                            @php
                                                $bahn++;
                                            @endphp
                                            <label for="name">Bahn:</label>
                                            {{ $bahn}}
                                            @if($lane->mannschaft_id!=Null)
                                                {{ $lane->regattaTeam->teamname }}
                                                @if($lane->regattaTeam->beschreibung != Null)
                                                    <a href="/Sprecher/Mannschaft/{{ $lane->mannschaft_id }}/{{ $raceNext1->id }}" class="me-2">
                                                        <button type="button" class="btn btn-secondary ml-2">Info</button>
                                                    </a>
                                                @endif
                                            @else
                                                frei
                                            @endif
                                            <br>
                                        @endforeach
                                    </p>
                                    @if($raceNext1->beschreibung)
                                        <hr></hr>
                                        <h3>Beschreibung zum Rennen</h3>
                                        <p>
                                            {!! $raceNext1->beschreibung !!}
                                        </p>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box">
                            <!-- Content for the second box -->
                            @if($raceNext2    == Null && $raceResoult2  == Null)
                                <p>Es sind keine Rennen vorhanden.</p>
                            @endif
                            @if($raceNext2    != Null && $raceResoult2  == Null)
                                @php
                                    $bahn=0;
                                @endphp
                                @if(is_numeric($raceNext2->nummer))
                                    <h2>{{ $raceNext2->nummer }}. {{ $raceNext2->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $raceNext2->nummer }} / {{ $raceNext2->rennBezeichnung }}</h2>
                                @endif
                                <p>
                                    @if($raceNext2->status < 2)
                                        <br>Rennen noch nicht gesetzt<br><br>
                                    @else
                                          @foreach($lanesNext2 as $lane)
                                                @php
                                                    $bahn++;
                                                @endphp
                                                <label for="name">Bahn:</label>
                                                {{ $bahn}}
                                                @if($lane->mannschaft_id!=Null)
                                                    {{ $lane->regattaTeam->teamname }}
                                                    @if($lane->regattaTeam->beschreibung != Null)
                                                        <a href="/Sprecher/Mannschaft/{{ $lane->mannschaft_id }}/{{ $raceNext2->id }}" class="me-2">
                                                            <button type="button" class="btn btn-secondary ml-2">Info</button>
                                                        </a>
                                                    @endif
                                                @else
                                                    frei
                                                @endif
                                                <br>
                                          @endforeach
                                    @endif
                                    @php
                                        $to   = explode(":" , $raceNext2->rennUhrzeit);
                                        $from = explode(":" , $raceNext2->verspaetungUhrzeit);
                                        $timto=$to[0]*60+$to[1];
                                        $timfrom=$from[0]*60+$from[1];
                                        $diff_in_minutes=$timfrom-$timto;
                                    @endphp
                                    Startzeit: {{ date("H:i", strtotime($raceNext2->rennUhrzeit)) }} Uhr
                                    @if($diff_in_minutes>5 && ($raceNext2->programmDatei != Null && $raceNext2->ergebnisDatei == Null) || ($raceNext2->status <= 2))
                                        <br>Voraussichtlich: {{ date("H:i", strtotime($raceNext2->verspaetungUhrzeit)) }} Uhr
                                    @endif
                                </p>
                                @if($raceNext2->beschreibung)
                                    <hr></hr>
                                    <h3>Beschreibung zum Rennen</h3>
                                    <p>
                                        {!! $raceNext2->beschreibung !!}
                                    </p>
                                @endif
                            @endif
                            @if($raceResoult2 != Null && $raceNext2     == Null &&$victoCremony2 == 0)
                                @if(is_numeric($raceResoult2->nummer))
                                    <h2>{{ $raceResoult2->nummer }}. {{ $raceResoult2->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $raceResoult2->nummer }} / {{ $raceResoult2->rennBezeichnung }}</h2>
                                @endif
                                @if($victoCremony2==0)
                                    <p>
                                        @php
                                            $platz=0
                                        @endphp
                                        @foreach($lanesResoult2 as $lane)
                                            @php
                                                $platz++
                                            @endphp
                                            <label for="name">Platz:</label>
                                            {{ $platz }}
                                            @if($lane->mannschaft_id!=Null)
                                                {{ $lane->regattaTeam->teamname }}
                                                @if($lane->regattaTeam->beschreibung != Null)
                                                    <a href="/Sprecher/Mannschaft/{{ $lane->mannschaft_id }}/{{ $raceResoult2->id }}" class="me-2">
                                                        <button type="button" class="btn btn-secondary ml-2">Info</button>
                                                    </a>
                                                @endif
                                            @endif
                                            <br>
                                        @endforeach
                                    </p>
                                    @if($raceResoult2->raceTabele->ueberschrift != Null)
                                        <hr class="my-4">
                                        <h3>Tabelle</h3>
                                        <p>
                                            {{ $raceResoult2->raceTabele->ueberschrift }}<br>
                                            @if($raceResoult2->raceTabele->fileTabelleDatei != Null)
                                                <a href="{{env('VEREIN_URL')}}/storage/tabeleDokumente/{{ $raceResoult2->raceTabele->fileTabelleDatei }}" target="_blank">
                                                    Tabellen Dokument
                                                </a>
                                                <br>
                                            @endif
                                            Akuallisiert:
                                            {{ date("d.m.y", strtotime($raceResoult2->raceTabele->updated_at)) }} {{ date("H:i", strtotime($raceResoult2->raceTabele->updated_at)) }} Uhr
                                        </p>
                                    @endif
                                    @if($raceResoult2->ergebnisBeschreibung)
                                        <hr></hr>
                                        <h3>Beschreibung zum Ergebnis</h3>
                                        <p>
                                            {!! $raceResoult2->ergebnisBeschreibung !!}
                                        </p>
                                    @endif
                                @endif
                            @endif
                            @if($raceNext2 != Null && $raceResoult2 != Null && $victoCremony2 == 1)
                                @php
                                    $bahn=0;
                                @endphp
                                @if(is_numeric($raceNext2->nummer))
                                    <h2>{{ $raceNext2->nummer }}. {{ $raceNext2->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $raceNext2->nummer }} / {{ $raceNext2->rennBezeichnung }}</h2>
                                @endif
                                @if($raceNext2->status < 2)
                                        <p>Rennen noch nicht gesetzt</p>
                                @else
                                    <p>
                                        Ergebnis wird auf der Siegerehrung bekannt gegeben.
                                    </p>
                                    <hr></hr>
                                    <p>
                                        @foreach($lanesNext2 as $lane)
                                            @php
                                                $bahn++;
                                            @endphp
                                            <label for="name">Bahn:</label>
                                            {{ $bahn}}
                                            @if($lane->mannschaft_id!=Null)
                                                {{ $lane->regattaTeam->teamname }}
                                                @if($lane->regattaTeam->beschreibung != Null)
                                                    <a href="/Sprecher/Mannschaft/{{ $lane->mannschaft_id }}/{{ $raceNext2->id }}" class="me-2">
                                                        <button type="button" class="btn btn-secondary ml-2">Info</button>
                                                    </a>
                                                @endif
                                            @else
                                                frei
                                            @endif
                                            <br>
                                        @endforeach
                                    </p>
                                    @if($raceNext2->beschreibung)
                                        <hr></hr>
                                        <h3>Beschreibung zum Rennen</h3>
                                        <p>
                                            {!! $raceNext2->beschreibung !!}
                                        </p>
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
