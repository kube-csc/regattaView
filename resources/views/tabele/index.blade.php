@extends('layouts.frontend')

@section('title' ,'Tabellen')

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
                @foreach($tabeles as $tabele)
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
                            <h4 class="title">{{ $tabele->ueberschrift }}</h4>
                            @if($tabele->tabelleDatei != Null)
                                <p><a href="{{env('VEREIN_URL')}}/storage/tabeleDokumente/{{ $tabele->tabelleDatei }}" target="_blank">
                                        <i class="bx bxs-file-doc"></i>Tabelle
                                    </a>
                                </p>
                            @endif
                            @if($tabele->beschreibung != '')
                              <b>Notiz zur Tabelle:</b><br>
                              <p>{!!  $tabele->beschreibung !!}</p>
                            @endif
                            @php /*
                             ToDo: Ausgeblendet weil die Vereinsverwaltung noch die Serverzeit und nicht die Locale Zeit speichert
                            <!-- <p>{ $tabele->updated_at->diffForHumans() }}</p> -->
                            <p>geändert am<br>
                               {{ date("d.m.y", strtotime($tabele->updated_at)) }} um {{ date("H:i", strtotime($tabele->updated_at)) }} Uhr
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
