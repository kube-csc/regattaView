@extends('layouts.frontend')

@section('title' ,'Tabellen '.$eventname)

@section('content')
    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container">
                <div class="section-title" data-aos="fade-in" data-aos-delay="50">
                    <h2>{{ $ueberschrift }}</h2>
                    {{-- Filteranzeige und Buttons --}}
                    <div class="d-flex flex-column align-items-center mb-2">
                        @php
                            $filterTeam = null;
                            if(session('team_filter')) {
                                $filterTeam = \App\Models\RegattaTeam::with('teamWertungsGruppe')->find(session('team_filter'));
                            }
                            $filterActive = session('team_filter_active', true);
                            $filterPossible = session('team_filter_possible', true);
                        @endphp
                        @if($filterPossible && $filterTeam)
                            <div class="mb-1" style="font-size:0.8em;">
                                <span class="badge m-1"
                                      style="background-color: #ff9800; color: #fff; font-size: 0.85em; padding: 0.2em 0.6em;">
                                    Gefiltert nach: {{ $filterTeam->teamname }}
                                    @if(!$filterActive)
                                        [Filter aus]
                                    @endif
                                </span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-center align-items-center mb-2 flex-wrap">
                            @if($filterPossible)
                                <a href="{{ route('program.selectTeamFilter') }}" class="me-2 mb-1">
                                    <button type="button" class="btn btn-secondary rounded-lg m-1 btn-sm">
                                        Mannschaft filtern
                                    </button>
                                </a>
                                @if($filterTeam)
                                    <form method="POST" action="{{ route('program.setTeamFilter') }}" class="mb-1">
                                        @csrf
                                        <input type="hidden" name="toggle" value="1">
                                        <button type="submit"
                                                class="btn {{ $filterActive ? 'btn-warning' : 'btn-success' }} btn-sm rounded-lg m-1"
                                                style="font-size: 0.95em; padding: 0.3em 1em;">
                                            Filter {{ $filterActive ? 'aus' : 'an' }}
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
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
                @foreach($tabeles as $table)
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
                            <h4 class="title">{{ $table->ueberschrift }}</h4>
                            <p><a href="/Tabelle/{{$table->id}}">
                                    <i class="bx bxs-label"></i>Tabellenausgabe
                               </a>
                            </p>
                            @if($table->tabelleDatei != Null)
                                <p><a href="{{env('VEREIN_URL')}}/storage/tabeleDokumente/{{ $table->tabelleDatei }}" target="_blank">
                                        <i class="bx bxs-file-doc"></i>Tabelle
                                   </a>
                                </p>
                            @endif
                            @if($table->beschreibung != '')
                              <b>Notiz zur Tabelle:</b><br>
                              <p>{!! $table->beschreibung !!}</p>
                            @endif
                            <p>geändert am<br>
                               {{ date("d.m.y", strtotime($table->updated_at)) }} um {{ date("H:i", strtotime($table->updated_at)) }} Uhr
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
