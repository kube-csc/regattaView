@extends('layouts.frontend')

@section('title' ,'Dokumente')

@section('content')
    <main id="main">
        <!-- ======= Services Section ======= -->
          <section id="about" class="about">
            <div class="container">
                <div class="section-title" data-aos="fade-in" data-aos-delay="50">
                    <h2>Dokumente</h2>
                     @if($eventDokumentes->count()>0)
                        @php
                            $groupflak=0;
                            $verwendung = [
                                 "2" => "Ausschreibung",
                                 "3" => "Programm",
                                 "4" => "Ergebnisse",
                                 "5" => "Plakat/Flyer",
                             ];
                        @endphp

                        <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="100">
                             @foreach($eventDokumentes as $eventDokumente)
                                @if($loop->first)
                                    @php
                                        $groupflak=$eventDokumente->verwendung;
                                    @endphp
                                    <ul style="list-style-type: none;">
                                        <li>{{ $verwendung[$groupflak] }}</li>
                                        <ul style="list-style-type: none;">
                                            @else
                                                @if($eventDokumente->verwendung != $groupflak)
                                                    @php
                                                        $groupflak=$eventDokumente->verwendung;
                                                    @endphp
                                        </ul>
                                    </ul>
                                    <ul style="list-style-type: none;">
                                        <li>{{ $verwendung[$groupflak] }}</li>
                                        <ul style="list-style-type: none;">
                                            @endif
                                            @endif
                                            @if( $eventDokumente->bild != NULL)
                                                <li><a href="{{env('VEREIN_URL')}}/storage/eventDokumente/{{ $eventDokumente->bild }}" target="_blank">{{ $eventDokumente->titel }}</a></li>
                                            @else
                                                <li><a href="{{env('VEREIN_URL')}}/daten/text/{{ $eventDokumente->image }}" target="_blank">{{ $eventDokumente->titel }}</a></li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </ul>
                        </div>
                </div>
            </div><!-- End .content-->
            </div>
            @endif

        </section><!-- End Services Section -->
    </main><!-- End #main -->
@endsection
