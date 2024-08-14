@extends('layouts.frontend')

@section('title' ,'Banbelegung')

@section('content')

    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container">
                <div class="section-title" data-aos="fade-in" data-aos-delay="50">
                    <h2>{{ $ueberschrift }}</h2>
                    <p>
                        <label for="name">Nummer:</label>
                        {{ $race->nummer }} {{ $race->rennBezeichnung }}
                        <br>
                        @if($race->raceTabele->ueberschrift)
                            <label for="name">Tabelle:</label>
                            {{ $race->raceTabele->ueberschrift }}
                            @if($race->mix==1)
                               <br>Mix Rennen
                            @endif
                        @endif
                        @php
                            $rennUhrzeitAlt= substr($race->rennUhrzeit, 0, -3);
                        @endphp
                        <br>
                        <label for="name">Startzeit:</label>
                        {{ $rennUhrzeitAlt }} Uhr {{ \Carbon\Carbon::parse($race->rennDatum)->format('d.m.Y') }}
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
                    <br>
                    <p>
                        @foreach($lanes as $lane)
                                 <label for="name">Bahn:</label>
                                {{ $lane->bahn }} {{ $lane->regattaTeam->teamname }}
                                <br><br>
                        @endforeach
                    </p>
                </div>
            </div>
        </section><!-- End Services Section -->
    </main><!-- End #main -->

@endsection
