@extends('layouts.speeker')

@section('title' ,'Sprecher Programm '.$event->ueberschrift)

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
                                        {{ $event->ueberschrift }}
                                        @foreach($racesChoose as $race)
                                            <option value="{{ $race->id }}"
                                                @if(isset($raceNext1) && $race->id == $raceNext1->id) selected @endif
                                                @if(isset($raceResoult1) && $race->id == $raceResoult1->id) selected @endif
                                                >
                                                @if(is_numeric($race->nummer))
                                                    <h2>{{ $race->nummer }}. {{ $race->rennBezeichnung }} - {{ date("H:i", strtotime($race->rennUhrzeit)) }} Uhr</h2>
                                                @else
                                                    <h2>{{ $race->nummer }} / {{ $race->rennBezeichnung }} - {{ date("H:i", strtotime($race->rennUhrzeit)) }} Uhr</h2>
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-secondary me-2 ml-2">Auswahl</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box">
                            @if($raceNext1 != Null)
                                <a href="/Sprecher/{{ $raceNext1->id  }}" class="me-2">
                                   <button type="button" class="btn btn-secondary ml-2 px-4">
                                        <i class="bx bx-refresh"></i>
                                    </button>
                                </a>
                            @elseif($raceResoult1->id != Null)
                                <a href="/Sprecher/{{ $raceResoult1->id }}" class="me-2">
                                    <button type="button" class="btn btn-secondary ml-2 px-4">
                                        <i class="bx bx-refresh"></i>
                                    </button>
                                </a>
                            @endif
                                <a href="/Sprecher" class="me-2">
                                    <button type="button" class="btn btn-secondary ml-2 px-4">
                                        <i class="bx bx-time"></i>
                                    </button>
                                </a>
                            @if($nachId>0)
                                <a href="/Sprecher/{{ $nachId }}" class="me-2">
                                    <button type="button" class="btn btn-secondary ml-2 px-4">
                                        <i class="bx bx-left-arrow-alt"></i>
                                    </button>
                                </a>
                            @else
                                <button type="button" class="btn btn-secondary ml-2 px-4">&nbsp;</button>
                            @endif
                            @if($vorId>0)
                                <a href="/Sprecher/{{ $vorId }}">
                                    <button type="button" class="btn btn-secondary ml-2 px-4">
                                        <i class="bx bx-right-arrow-alt"></i>
                                    </button>
                                </a>
                            @else
                                <button type="button" class="btn btn-secondary ml-2 px-4">&nbsp;</button>
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
                            @if($raceNext1 == Null && $raceResoult1 == Null)
                                <p>Es sind keine Rennen vorhanden.</p>
                            @endif
                            @if($raceNext1 != Null && $raceResoult1 == Null)
                                @if(is_numeric($raceNext1->nummer))
                                    <h2>{{ $raceNext1->nummer }}. {{ $raceNext1->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $raceNext1->nummer }} / {{ $raceNext1->rennBezeichnung }}</h2>
                                @endif
                                @if($raceNext1->mix == 1)
                                    <p class="text-primary">Dieses Rennen wird in mehreren Klassen gewertet!</p>
                                @endif
                                <p>
                                    @if($raceNext1->status < 2)
                                        <br>Rennen noch nicht gesetzt<br><br>
                                    @else
                                    @foreach($lanesNext1 as $lane)
                                        @include('components.raceProgram', ['raceNext' => $raceNext1])
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
                                    <hr />
                                    <h3>Beschreibung zum Rennen</h3>
                                    <p>
                                        {!! $raceNext1->beschreibung !!}
                                    </p>
                                @endif
                            @endif
                            @if($raceResoult1 != Null && $victoCremony1 == 1)
                                @if(is_numeric($raceResoult1->nummer))
                                    <h2>{{ $raceResoult1->nummer }}. {{ $raceResoult1->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $raceResoult1->nummer }} / {{ $raceResoult1->rennBezeichnung }}</h2>
                                @endif
                                @if($raceResoult1->mix == 1)
                                    <p class="text-primary">Dieses Rennen wird in mehreren Klassen gewertet!</p>
                                @endif
                                <p>
                                    @php
                                      $platz=0
                                    @endphp
                                    @foreach($lanesResoult1 as $lane)
                                        @php
                                            $platz++
                                        @endphp
                                        @include('components.raceRecoult', ['raceResoult' => $raceResoult1])
                                    @endforeach
                                </p>
                                {{-- Tabellenausgabe links Seite--}}
                                @if($raceResoult1->raceTabele->ueberschrift != Null && $raceResoult1->raceTabele->tabelleVisible == 1 && $raceResoult1->raceTabele->wertungsart != 3)
                                   <hr />
                                   <div class="my-4">
                                       @if($victoCremonyTable1 == 1)
                                           <h2>
                                             <a href="/Sprecher/Tabelle/{{ $raceResoult1->raceTabele->id }}/{{ $raceResoult1->id }}" class="me-2">
                                                <button type="button" class="btn btn-primary ml-2">Tabelle</button>
                                             </a>
                                             {{ $raceResoult1->raceTabele->ueberschrift }}
                                           </h2>
                                       @else
                                           <h2>Tabelle - {{ $raceResoult1->raceTabele->ueberschrift }}</h2>

                                           Das Ergebnis wird auf der Siegerehrung bekannt gegeben.
                                       @endif

                                       @if($tabeledatas1 && $victoCremonyTable1 == 1)
                                          <p>
                                          @foreach($tabeledatas1 as $tabeledata)
                                              @include('components.table', [
                                                                             'tabeledata'  => $tabeledata,
                                                                             'raceResoult' => $raceResoult1
                                                                           ])
                                          @endforeach
                                          @if($raceResoult1->raceTabele->fileTabelleDatei != Null)
                                              <hr />
                                              <a href="{{env('VEREIN_URL')}}/storage/tabeleDokumente/{{ $raceResoult1->raceTabele->tabelleDatei }}" target="_blank">
                                                  <i class="bx bxs-file-doc"></i>Tabellen Dokument
                                              </a>
                                          @endif
                                          </p>
                                      @endif
                                   </div>
                                @endif
                                @if($raceResoult1->ergebnisBeschreibung && $victoCremony1 == 1)
                                    <hr />
                                    <h3>Beschreibung zum Ergebnis</h3>
                                    <p>
                                        {!! $raceResoult1->ergebnisBeschreibung !!}
                                    </p>
                                @endif
                            @endif
                            @if($raceNext1 != Null && $raceResoult1 != Null && $victoCremony1 == 0)
                                @php
                                    $bahn=0;
                                @endphp
                                @if(is_numeric($raceNext1->nummer))
                                    <h2>{{ $raceNext1->nummer }}. {{ $raceNext1->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $raceNext1->nummer }} / {{ $raceNext1->rennBezeichnung }}</h2>
                                @endif                                @if($raceNext1->mix == 1)
                                    <p class="text-primary">Dieses Rennen wird in mehreren Klassen gewertet!</p>
                                @endif
                                @if($raceNext1->status < 2)
                                    <p>Rennen noch nicht gesetzt</p>
                                @else
                                    <p>
                                        Ergebnis wird auf der Siegerehrung bekannt gegeben.
                                    </p>
                                    <hr />
                                    <p>
                                        <!-- ToDo: Noch mit include einfügen -->
                                        @foreach($lanesNext1 as $lane)
                                            <label for="name">Bahn:</label>
                                            {{ $lane->bahn}}
                                            @if($lane->mannschaft_id!=Null)
                                                @if($lane->regattaTeam->beschreibung != Null)
                                                    <a href="/Sprecher/Mannschaft/{{ $lane->mannschaft_id }}/{{ $raceNext1->id }}" class="me-2">
                                                        <button type="button" class="btn btn-secondary ml-2"> {{ $lane->regattaTeam->teamname }}</button>
                                                    </a>
                                                @else
                                                    {{ $lane->regattaTeam->teamname }}
                                                @endif
                                                @if($raceNext1->mix == 1 && $lane->tabele_id <> $raceNext1->tabele_id)
                                                    <a href="/Sprecher/Tabelle/{{ $lane->tabele_id }}/{{ $raceNext1->id }}" class="me-2">
                                                        <button type="button" class="btn btn-primary ml-2">{{ $lane->getTableLane->ueberschrift }}</button>
                                                    </a>
                                                @endif
                                            @else
                                                frei
                                            @endif
                                            <br>
                                        @endforeach
                                    </p>
                                    @if($raceNext1->beschreibung)
                                        <hr/>
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
                            @if($raceNext2 == Null && $raceResoult2 == Null)
                                <p>Es sind keine Rennen vorhanden.</p>
                            @endif
                            @if($raceNext2 != Null && $raceResoult2 == Null)
                                @php
                                    $bahn=0;
                                @endphp
                                @if(is_numeric($raceNext2->nummer))
                                    <h2>{{ $raceNext2->nummer }}. {{ $raceNext2->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $raceNext2->nummer }} / {{ $raceNext2->rennBezeichnung }}</h2>
                                @endif
                                @if($raceNext2->mix == 1)
                                    <p class="text-primary">Dieses Rennen wird in mehreren Klassen gewertet!</p>
                                @endif
                                <p>
                                    @if($raceNext2->status < 2)
                                        <br>Rennen noch nicht gesetzt<br><br>
                                    @else
                                          @foreach($lanesNext2 as $lane)
                                               @include('components.raceProgram', ['raceNext' => $raceNext2])
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
                                    <hr />
                                    <h3>Beschreibung zum Rennen</h3>
                                    <p>
                                        {!! $raceNext2->beschreibung !!}
                                    </p>
                                @endif
                            @endif
                            @if($raceNext2 != Null && $raceResoult2 != Null && $victoCremony2 == 1)
                                @if(is_numeric($raceResoult2->nummer))
                                    <h2>{{ $raceResoult2->nummer }}. {{ $raceResoult2->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $raceResoult2->nummer }} / {{ $raceResoult2->rennBezeichnung }}</h2>
                                @endif
                                @if($raceResoult2->mix == 1)
                                    <p class="text-primary">Dieses Rennen wird in mehreren Klassen gewertet!</p>
                                @endif
                                @if($victoCremony2 == 1)
                                    <p>
                                        @php
                                            $platz=0
                                        @endphp
                                        @foreach($lanesResoult2 as $lane)
                                            @php
                                                $platz++
                                            @endphp
                                            @include('components.raceRecoult', ['raceResoult' => $raceResoult2])
                                        @endforeach
                                    </p>
                                    {{-- Tabellenausgabe rechts Seite--}}
                                    @if($raceResoult2->raceTabele->ueberschrift != Null && $raceResoult2->raceTabele->tabelleVisible == 1 && $raceResoult2->raceTabele->wertungsart != 3)
                                        <hr />
                                        <div class="my-4">
                                            @if($victoCremonyTable2 == 1)
                                               <h2>
                                                 <a href="/Sprecher/Tabelle/{{ $raceResoult2->raceTabele->id }}/{{ $raceResoult2->id }}" class="me-2">
                                                    <button type="button" class="btn btn-primary ml-2">Tabelle</button>
                                                 </a>
                                                 {{ $raceResoult2->raceTabele->ueberschrift }}
                                               </h2>
                                            @else
                                               <h2>Tabelle - {{ $raceResoult2->raceTabele->ueberschrift }}</h2>

                                                Das Ergebnis wird auf der Siegerehrung bekannt gegeben.
                                            @endif

                                            @if($tabeledatas2 && $victoCremonyTable2 == 1)
                                               <p>
                                                  @foreach($tabeledatas2 as $tabeledata)
                                                        @include('components.table', [
                                                                                       'tabeledata'  => $tabeledata,
                                                                                       'raceResoult' => $raceResoult2
                                                                                     ])
                                                  @endforeach
                                                  @if($raceResoult2->raceTabele->fileTabelleDatei != Null)
                                                      <hr />
                                                      <a href="{{env('VEREIN_URL')}}/storage/tabeleDokumente/{{ $raceResoult2->raceTabele->tabelleDatei }}" target="_blank">
                                                          <i class="bx bxs-file-doc"></i>Tabellen Dokument
                                                      </a>
                                                  @endif
                                               </p>
                                            @endif
                                        </div>
                                    @endif
                                    @if($raceResoult2->ergebnisBeschreibung && $victoCremony2 == 1)
                                        <hr />
                                        <h3>Beschreibung zum Ergebnis</h3>
                                        <p>
                                            {!! $raceResoult2->ergebnisBeschreibung !!}
                                        </p>
                                    @endif
                                @endif
                            @endif
                            @if($raceNext2 != Null && $raceResoult2 != Null && $victoCremony2 == 0)
                                @php
                                    $bahn=0;
                                @endphp
                                @if(is_numeric($raceNext2->nummer))
                                    <h2>{{ $raceNext2->nummer }}. {{ $raceNext2->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $raceNext2->nummer }} / {{ $raceNext2->rennBezeichnung }}</h2>
                                @endif
                                @if($raceNext2->mix == 1)
                                    <p class="text-primary">Dieses Rennen wird in mehreren Klassen gewertet!</p>
                                @endif
                                @if($raceNext2->status < 2)
                                        <p>Rennen noch nicht gesetzt</p>
                                @else
                                    <p>
                                        Ergebnis wird auf der Siegerehrung bekannt gegeben.
                                    </p>
                                    <hr/>
                                    <p>
                                        @foreach($lanesNext2 as $lane)
                                            @include('components.raceProgram', ['raceNext' => $raceNext2])
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
