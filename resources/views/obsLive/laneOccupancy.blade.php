@extends('layouts.obs')

@section('content')

    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="about" class="about">
            <div class="container">
               <div class="section-title">
                    <h2>{{ $race->rennBezeichnung }}</h2>
                    <p>
                        @php
                            $bahn=0
                        @endphp
                        @foreach($lanes as $lane)
                            @php
                                $bahn++
                            @endphp
                            <label for="name">Bahn:</label>
                            {{ $bahn}} {{ $lane->regattaTeam->teamname }}
                            <br>
                        @endforeach
                        @php
                            $to   = explode(":" , $race->rennUhrzeit);
                            $from = explode(":" , $race->verspaetungUhrzeit);
                            $timto=$to[0]*60+$to[1];
                            $timfrom=$from[0]*60+$from[1];
                            $diff_in_minutes=$timfrom-$timto;
                        @endphp
                        Startzeit: {{ date("H:i", strtotime($race->rennUhrzeit)) }} Uhr
                        @if($diff_in_minutes>5 && ($race->programmDatei != Null && $race->ergebnisDatei == Null) || ($race->status == 2))
                            <br>Voraussichtlich: {{ date("H:i", strtotime($race->verspaetungUhrzeit)) }} Uhr
                        @endif
                    </p>
                </div>

            </div>
        </section>
    </main>

@endsection