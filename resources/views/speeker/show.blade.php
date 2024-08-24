@extends('layouts.speeker')

@section('content')

    <main id="main">
        <!-- ======= Search Section ======= -->
        <section id="search" class="search">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="box">
                            <form action="/Sprecher/Auswahl" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="search-box d-flex">
                                    <select name="speekerId" id="speekerId" class="form-control me-2">
                                        @foreach($raceChooses as $race)
                                            <option value="{{ $race->id }}">{{ $race->rennBezeichnung }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-secondary me-2 ml-2">auswahl</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="box">
                            @if($raceNext1!= Null)
                                <a href="/Sprecher/{{ $raceNext1->id  }}" class="me-2">
                                    <button type="button" class="btn btn-secondary ml-2">akuallisieren</button>
                                </a>
                            @elseif($raceResoult1->id != Null)
                                <a href="/Sprecher/{{ $raceResoult1->id }}" class="me-2">
                                    <button type="button" class="btn btn-secondary ml-2">akuallisieren</button>
                                </a>
                            @endif
                            <a href="/Sprecher" class="me-2">
                                <button type="button" class="btn btn-secondary ml-2">Aktuell</button>
                            </a>
                            @if($nachId>0)
                            <a href="/Sprecher/{{ $nachId }}" class="me-2">
                                <button type="button" class="btn btn-secondary ml-2">Zurück</button>
                            </a>
                            @endif
                            @if($vorId>0)
                            <a href="/Sprecher/{{ $vorId }}">
                                <button type="button" class="btn btn-secondary ml-2">Weiter</button>
                            </a>
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
                            @if($raceNext1==Null  && $raceResoult1==Null)
                                <h2>Es sind keine Rennen vorhanden</h2>
                            @endif
                            @if($raceNext1!=Null && $raceResoult1==Null)
                                @php
                                    $bahn=0;
                                @endphp
                                <h2>{{ $raceNext1->rennBezeichnung }}</h2>
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
                                    @if($diff_in_minutes>5 && ($raceNext1->programmDatei != Null && $race->ergebnisDatei == Null) || ($raceNext1->status <= 2))
                                        <br>Voraussichtlich: {{ date("H:i", strtotime($raceNext1->verspaetungUhrzeit)) }} Uhr
                                    @endif

                                </p>
                            @endif
                            @if($raceResoult1!=Null)
                                <h2>{{ $raceResoult1->rennBezeichnung }}</h2>
                                @if($victoCremony1==0)
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
                                @else
                                    <p>
                                        Ergebnis wird auf der Siegerehrung bekannt gegeben.
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box">
                            <!-- Content for the second box -->
                            @if($raceNext2==Null && $raceResoult2==Null)
                                <h2>Es sind keine Rennen vorhanden</h2>
                            @endif
                            @if($raceNext2!=Null)
                                @php
                                    $bahn=0;
                                @endphp
                                <h2>{{ $raceNext2->rennBezeichnung }}</h2>
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
                            @endif
                            @if($raceResoult2!=Null && $raceNext2==Null)
                                <h2>{{ $raceResoult2->rennBezeichnung }}</h2>
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
                                            @endif
                                            <br>
                                        @endforeach
                                    </p>
                                @else
                                    <p>
                                        Ergebnis wird auf der Siegerehrung bekannt gegeben.
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
