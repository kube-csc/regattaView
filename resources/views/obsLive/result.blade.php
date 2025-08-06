@extends('layouts.obs')

@section('content')

    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="about" class="about">
            <div class="container">
                <div class="section-box">
                    <h2>
                        @if(is_numeric($race->nummer))
                            {{ $race->nummer }}. {{ $race->rennBezeichnung }}
                        @else
                            {{ $race->nummer }} / {{ $race->rennBezeichnung }}
                        @endif
                    </h2>
                    @if($victoCremony==0)
                        <p>
                            @foreach($lanes as $lane)
                                <label for="name">Platz:</label>
                                {{ $lane->platz }}
                                @if($lane->mannschaft_id != Null)
                                    {{ $lane->regattaTeam->teamname }}
                                @endif
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
