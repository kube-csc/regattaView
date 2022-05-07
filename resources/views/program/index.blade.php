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
                           <button type = "button" class = "btn btn-primary rounded-bottom m-2">alle Rennen</button>
                       </a>
                       <a href="/Programm/geplante">
                           <button type = "button" class = "btn btn-primary rounded-bottom m-2">geplante Rennen</button>
                       </a>
                       <a href="/Ergebnisse">
                           <button type = "button" class = "btn btn-primary rounded-bottom m-2">gewertete Rennen</button>
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
                            <p class="description">am {{ date("d.m.Y", strtotime($race->datumvon)) }} um {{ date("H:i", strtotime($race->uhrzeit)) }}</p>
                            @if($race->beschreibung != '')
                                <b>Notiz zum Rennen:</b><br>
                                <p>{!!  $race->beschreibung !!}</p>
                            @endif
                            @if($race->programmDatei != Null)
                                <p><a href="{{env('Verein_URL')}}/storage/raceDokumente/{{ $race->programmDatei }}" target="_blank">
                                        <i class="bx bxs-file-doc"></i>Programm
                                    </a>
                                </p>
                            @endif
                            @if($race->ergebnisDatei != Null)
                              <p><a href="{{env('Verein_URL')}}/storage/raceDokumente/{{ $race->ergebnisDatei }}" target="_blank">
                                      <i class="bx bxs-file-doc"></i>Ergebnisse
                                  </a>
                              </p>
                            @endif
                            @if($race->ergebnisBeschreibung != '')
                              <b>Notiz zum Ergebnis:</b><br>
                              <p>{!!  $race->ergebnisBeschreibung !!}</p>
                            @endif
                            @php /*
                             ToDo: Ausgeblendet weil die Vereinsverwaltung noch die Ververzeit und nicht die Locale Zeit speichert
                            <!-- <p>{ $race->updated_at->diffForHumans() }}</p> -->
                            <p>geändert am<br>
                               {{ date("d.m.y", strtotime($race->updated_at)) }} um {{ date("H:i", strtotime($race->updated_at)) }} Uhr
                            </p>
                            */
                            @endphp
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
                            <button type = "button" class = "btn btn-primary rounded-bottom m-2">alle Rennen</button>
                        </a>
                        <a href="/Programm/geplante">
                            <button type = "button" class = "btn btn-primary rounded-bottom m-2">geplante Rennen</button>
                        </a>
                        <a href="/Ergebnisse">
                            <button type = "button" class = "btn btn-primary rounded-bottom m-2">gewertete Rennen</button>
                        </a>
                </div>

            </div>
        </section><!-- End Services Section -->
    </main><!-- End #main -->
@endsection
