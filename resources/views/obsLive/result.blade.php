@extends('layouts.obs')

@section('content')


    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="about" class="about">
            <div class="container">
                <div class="section-title">
                    <h2>{{ $race->rennBezeichnung }}</h2>
                    @if($victoCremony==0)
                        <p>
                            @php
                                $platz=0
                            @endphp
                            @foreach($lanes as $lane)
                                @php
                                    $platz++
                                @endphp
                                <label for="name">Platz:</label>
                                {{ $platz }} {{ $lane->regattaTeam->teamname }}
                                <br>
                            @endforeach
                        </p>
                    @else
                        <p>
                            Ergebnis wird auf der Siegerehrung bekannt gegeben
                        </p>
                    @endif
                </div>
            </div>
        </section>
    </main>


@endsection