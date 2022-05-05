@foreach($events as $event)
<!-- ======= About Section ======= -->
<section id="about" class="about">
    <div class="container">

        <div class="row no-gutters">
            <div class="content col-xl-5 d-flex align-items-stretch" data-aos="fade-up">
                <div class="content">
                    <h3>{{ $event->ueberschrift }}</h3>
                    <p>
                        {!! $event->anmeldetext !!}
                    </p>
                    <!-- <a href="#" class="about-btn">About us <i class="bx bx-chevron-right"></i></a> -->
                </div>
            </div>
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
        </div>

    </div>
</section><!-- End About Section -->
@endforeach
