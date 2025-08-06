@extends('layouts.obs')

@section('content')

    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="about" class="about">
            <div class="container">
                <div class="section-race">
                    <p>
                       @if(is_numeric($race->nummer))
                            {{ $race->nummer }}. {{ $race->rennBezeichnung }}
                       @else
                            {{ $race->nummer }} / {{ $race->rennBezeichnung }}
                       @endif
                    </p>
                    @php
                        $to   = explode(":" , $race->rennUhrzeit);
                        $from = explode(":" , $race->verspaetungUhrzeit);
                        $timto=$to[0]*60+$to[1];
                        $timfrom=$from[0]*60+$from[1];
                        $diff_in_minutes=$timfrom-$timto;
                    @endphp
                    <p>
                    Startzeit: {{ date("H:i", strtotime($race->rennUhrzeit)) }} Uhr
                    @if($race->rennDatum == date("Y-m-d", strtotime(now())) && $diff_in_minutes > 5  && ($race->rennzeit == 0 && ($race->ergebnisDatei == Null or $race->status == 4)) )
                        <br>Voraussichtlich: {{ date("H:i", strtotime($race->verspaetungUhrzeit)) }} Uhr
                    @endif
                    @if($race->rennzeit == 1)
                        <br>gestartet: {{ date("H:i", strtotime($race->verspaetungUhrzeit)) }} Uhr
                    @endif
                    </p>
                </div>
            </div>
        </section>
    </main>

@endsection
