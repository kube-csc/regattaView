@if($eventDokumentes->count()>0)
    @php
        $groupflak=0;
        $verwendung = [
             "2" => "Ausschreibung",
             "3" => "Programm",
             "4" => "Ergebnisse",
             "5" => "Plakat / Flyer",
         ];
    @endphp
 <!-- ======= Cta Section ======= -->
 <section id="cta" class="cta">
    <div class="container" data-aos="zoom-in">

        <div class="text-center">
            <h3>Dokumente</h3>
            <p>
             Hier findet Ihr die Dokumente zum Event
            </p>
            @foreach($eventDokumentes as $eventDokumente)
               @php($groupflak=$eventDokumente->verwendung)
               <a class="cta-btn" href="{{env('Verein_URL')}}/storage/eventDokumente/{{ $eventDokumente->bild }}" target="_blank">{{ $verwendung[$groupflak] }}</a>
            @endforeach
        </div>

    </div>
 </section><!-- End Cta Section -->
@endif
