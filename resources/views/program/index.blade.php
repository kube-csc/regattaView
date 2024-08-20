@extends('layouts.frontend')

@section('title' ,'Programm')

@section('content')

    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container">
                <div class="section-title" data-aos="fade-in" data-aos-delay="50">
                    <h2>{{ $ueberschrift }}</h2>
                    <p>
                       <a href="/Programm">
                           <button type = "button" class = "btn btn-primary rounded-lg m-2">alle Rennen</button>
                       </a>
                       <a href="/Programm/geplante">
                           <button type = "button" class = "btn btn-primary rounded-lg m-2">geplante Rennen</button>
                       </a>
                       <a href="/Ergebnisse">
                           <button type = "button" class = "btn btn-primary rounded-lg m-2">gewertete Rennen</button>
                       </a>
                       <a href="/Tabellen">
                           <button type = "button" class = "btn btn-primary rounded-lg m-2">Tabellen</button>
                       </a>
                    </p>
                </div>

                @php
                    $i=0;
                    $delay=75;
                @endphp
                @foreach($races as $race)
                @if($i==0)
                <div class="row">
                @endif
                    @if ($loop->first)
                        <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                            <div class="icon-box" data-aos="fade-up">
                    @else
                        <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                            <div class="icon-box" data-aos="fade-up" data-aos-delay="{{ $delay }}">
                    @endif
                            <p>Rennen: {{ $race->nummer }}</p>
                            <h4 class="title">{{ $race->rennBezeichnung }}</h4>
                            <p class="description">
                                {{ date("d.m.Y", strtotime($race->rennDatum)) }} {{ date("H:i", strtotime($race->rennUhrzeit)) }} Uhr
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
                            @if($race->beschreibung != '')
                                <b>Notiz zum Rennen:</b><br>
                                <p>{!! $race->beschreibung !!}</p>
                            @endif
                            @if($race->programmDatei != Null)
                                <p><a href="{{env('VEREIN_URL')}}/storage/raceDokumente/{{ $race->programmDatei }}" target="_blank">
                                        <i class="bx bxs-file-doc"></i>Programm Dokument
                                    </a>
                                </p>
                            @endif
                            @if($race->status >= 2 && $race->status <= 4)
                                    <p><a href="/Bahnbelegung/{{$race->id}}">
                                            <i class="bx bxs-info-circle"></i>Bahnbelegung
                                        </a>
                                    </p>
                            @endif
                            @if($race->veroeffentlichungUhrzeit < Illuminate\Support\Carbon::now()->toTimeString() || $race->rennDatum < Illuminate\Support\Carbon::now()->toDateString())
                                @if($race->status == 4)
                                    <p><a href="/Ergebnis/{{$race->id}}">
                                            <i class="bx bxs-info-circle"></i>Ergebnis
                                        </a>
                                    </p>
                                @endif
                                @if($race->ergebnisDatei != Null)
                                    <p><a href="{{env('VEREIN_URL')}}/storage/raceDokumente/{{ $race->ergebnisDatei }}" target="_blank">
                                            <i class="bx bxs-file-doc"></i>Ergebnisse Dokument
                                        </a>
                                    </p>
                                @endif
                            @else
                                    @if($race->status >= 3 && $race->status <= 4)
                                        <p><a href="/Bahnbelegung/{{$race->id}}">
                                                <i class="bx bxs-info-circle"></i>Bahnbelegung
                                            </a>
                                            Ergebnisse bei der Siegerehrung
                                        </p>
                                    @endif
                            @endif
                            @if($race->ergebnisBeschreibung != '')
                              <b>Notiz zum Ergebnis:</b><br>
                              <p>{!! $race->ergebnisBeschreibung !!}</p>
                            @endif
                            <p>aktualisiert:<br>
                               {{ date("d.m.y", strtotime($race->updated_at)) }} {{ date("H:i", strtotime($race->updated_at)) }} Uhr
                            </p>
                        </div>
                    </div>
                    @if ($loop->last)
                      </div>
                      @php($i=0)
                    @endif
                @php(++$i)
                @if($i==4)
                </div>
                <br>
                  @php($i=0)
                @endif
                @php($delay=$delay+25)
                @endforeach

                <div class="section-title" data-aos="fade-in" data-aos-delay="{{ $delay }}">
                        <a href="/Programm">
                            <button type = "button" class = "btn btn-primary rounded-lg m-2">alle Rennen</button>
                        </a>
                        <a href="/Programm/geplante">
                            <button type = "button" class = "btn btn-primary rounded-lg m-2">geplante Rennen</button>
                        </a>
                        <a href="/Ergebnisse">
                            <button type = "button" class = "btn btn-primary rounded-lg m-2">gewertete Rennen</button>
                        </a>
                        <a href="/Tabellen">
                            <button type = "button" class = "btn btn-primary rounded-lg m-2">Tabellen</button>
                        </a>
                </div>

            </div>
        </section><!-- End Services Section -->
    </main><!-- End #main -->

@endsection
