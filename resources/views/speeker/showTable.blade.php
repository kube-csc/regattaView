@extends('layouts.speeker')

@section('title' ,'Sprecher Table '.$event->ueberschrift)

@section('content')

    <main id="main">
        <!-- ======= Search Section ======= -->
        <section id="search" class="search">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="box">
                            <form action="/Sprecher/Tabellen/Auswahl" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="raceId" name="raceId" value="{{ $raceId }}">
                                <div class="search-box d-flex">
                                    <select name="tableId" id="raceId" class="form-control me-2">
                                        @foreach($tabelChooses as $tabelChoose)
                                            <option value="{{ $tabelChoose->id }}" @selected($tabelChoose->id == $tableId)>
                                                {{ $tabelChoose->ueberschrift }}
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
                            <a href="/Sprecher/Tabelle/{{ $tableId }}/{{ $raceId }}" class="me-2">
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
                            <!-- Content for the left box -->
                            <h2>Tabelle - {{ $tableShow->ueberschrift }}</h2>
                            <p>
                                @if($victoCremonyTableShow == 0)
                                   Ergebnis wird auf der Siegerehrung bekannt gegeben.
                                @else
                                   @if($tabeledataShows)
                                      @foreach($tabeledataShows as $tabeledata)
                                        <b>Platz:
                                        {{ $tabeledata->platz }}</b> {{ $tabeledata->getMannschaft->teamname }}
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
                                      @endif
                                   @endif
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box">
                            <!-- Content for the right box -->
                            @if($lanes!=Null)
                                @php
                                    $bahn=0;
                                @endphp
                                @if(is_numeric($race->nummer))
                                    <h2>{{ $race->nummer }}. {{ $race->rennBezeichnung }}</h2>
                                @else
                                    <h2>{{ $race->nummer }} / {{ $race->rennBezeichnung }}</h2>
                                @endif
                                @if($race->mix == 1)
                                    <p class="text-primary">Dieses Rennen wird in mehreren Klassen gewertet!</p>
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
                                            <label for="name">Bahn:</label>
                                            {{ $lane->bahn }}
                                            @if($lane->mannschaft_id!=Null)
                                                {{ $lane->regattaTeam->teamname }}
                                                @if($lane->regattaTeam->beschreibung != Null)
                                                    <a href="/Sprecher/Mannschaft/{{ $lane->mannschaft_id }}/{{ $race->id }}" class="me-2">
                                                        <button type="button" class="btn btn-secondary ml-2">Info</button>
                                                    </a>
                                                @endif
                                            @endif
                                            <br>
                                        @endforeach
                                    </p>
                                    @if($race->beschreibung)
                                        <hr />
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
                                                @include('components.raceRecoult', ['raceResoult' => $race])
                                            @endforeach
                                        </p>
                                        @if($table->ueberschrift != Null && $table->tabelleVisible == 1)
                                           <hr />
                                           <div class="my-4">
                                               @if($victoCremonyTable == 1)
                                                   <h2>
                                                       <a href="/Sprecher/Tabelle/{{ $table->id }}/{{ $race->id }}" class="me-2">
                                                           <button type="button" class="btn btn-primary ml-2">Tabelle</button>
                                                       </a>
                                                       {{ $table->ueberschrift }}
                                                   </h2>
                                               @else
                                                   <h2>Tabelle - {{ $race->raceTabele->ueberschrift }}</h2>
                                                   Das Ergebnis wird auf der Siegerehrung bekannt gegeben.
                                               @endif
                                               <p>
                                                  @if($tabeledatas && $victoCremonyTable == 1)
                                                     @foreach($tabeledatas as $tabeledata)
                                                        <b>Platz:
                                                            {{ $tabeledata->platz }}</b> {{ $tabeledata->getMannschaft->teamname }}
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
                                                     @endif
                                                  @endif
                                              </p>
                                           </div>
                                        @endif
                                        @if($race->ergebnisBeschreibung && $victoCremony == 1)
                                            <hr />
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
