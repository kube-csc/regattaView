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
            <h3>Programm und Ergebnisse</h3>
            <p>
               Hier finden Sie das Programm und die Ergebnisse für jedes Rennen.
            </p>
            <a class="cta-btn" href="/Programm">alle Rennen</a>
            <a class="cta-btn" href="/Programm/geplante">geplante Rennen</a>
            <a class="cta-btn" href="/Ergebnisse">gewertete Rennen</a>
        </div>

    </div>
 </section><!-- End Cta Section -->
@endif
