@foreach($events as $event)
<!-- ======= About Section ======= -->
<section id="about" class="about">
    <div class="container">

        <div class="row no-gutters">
            <div class="content col-xl-5 d-flex align-items-stretch" data-aos="fade-up">
              @if($regattaInformations->count()==0)
                <div class="content">
                    <h3>{{ $event->ueberschrift }}</h3>
                    <p>
                        {!! $event->anmeldetext !!}
                    </p>
                    <!-- <a href="#" class="about-btn">About us <i class="bx bx-chevron-right"></i></a> -->
                </div>
              @else
                  @foreach($regattaInformations as $regattaInformation)
                    @if($loop->first)
                      <div class="content">
                          <h3>{{ $regattaInformation->informationTittel }}</h3>
                          <p>
                              {!! $regattaInformation->informationBeschreibung !!}
                          </p>
                      </div>
                    @endif
                  @endforeach
              @endif
            </div>
            @php
              $delay=50;
            @endphp
            <div class="col-xl-7 d-flex align-items-stretch">
                <div class="icon-boxes d-flex flex-column justify-content-center">
                    <div class="row">
            @foreach($regattaInformations as $regattaInformation)
                @if($loop->first)
                @else
                                <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="{{ $delay }}">
                                    <h4>{{ $regattaInformation->informationTittel }}</h4>
                                    <p>
                                        {!! $regattaInformation->informationBeschreibung !!}
                                    </p>
                                </div>
                    @php
                        $delay=$delay+50;
                    @endphp
                @endif
            @endforeach

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

                            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="{{ $delay }}">
                                <i class="bx bx-file"></i>
                                <h4>Dokumente:</h4>
                                @foreach($eventDokumentes as $eventDokumente)
                                    @if($loop->first)
                                        @php
                                            $groupflak=$eventDokumente->verwendung;
                                        @endphp
                                        <ul>
                                            <li>{{ $verwendung[$groupflak] }}</li>
                                            <ul>
                                    @else
                                               @if($eventDokumente->verwendung != $groupflak)
                                                   @php
                                                       $groupflak=$eventDokumente->verwendung;
                                                   @endphp
                                            </ul>
                                        </ul>
                                        <ul>
                                            <li>{{ $verwendung[$groupflak] }}</li>
                                            <ul>
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
            @php /*
            <div class="col-xl-7 d-flex align-items-stretch">
                <div class="icon-boxes d-flex flex-column justify-content-center">
                    <div class="row">
                        <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="100">
                            <i class="bx bx-calendar-event"></i>
                            <h4>Wann:</h4>
                            @if($event->datumvon == $event->datumbis)
                                <p>am {{ date("d.m.Y", strtotime($event->datumvon)) }}</p>
                            @else
                                <p>von {{ date("d.m.Y", strtotime($event->datumvon)) }}<br>
                                    bis {{ date("d.m.Y", strtotime($event->datumbis)) }}
                                </p>
                            @endif
                        </div>
                        <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="200">
                            <i class="bx bx-home"></i>
                            <h4>Anschrift:</h4>
                            <p>
                                {{ str_replace('_', ' ', env('Verein_Name')) }}<br>
                                {{ str_replace('_', ' ', env('Verein_Strasse')) }}<br>
                                {{ str_replace('_', ' ', env('Verein_PLZ')) }} {{ str_replace('_', ' ', env('Verein_Ort')) }}
                            </p>
                        </div>
                        <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="300">
                            <!-- <i class="bx bx-images"></i> -->
                            <h4></h4>
                            <p></p>
                        </div>
                        <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="400">
                            <!-- <i class="bx bx-shield"></i>  -->
                            <h4></h4>
                            <p></p>
                        </div>
                    </div>
                </div><!-- End .content-->
            </div>
            */
            @endphp
        </div>
    </div>
</section><!-- End About Section -->
@endforeach
