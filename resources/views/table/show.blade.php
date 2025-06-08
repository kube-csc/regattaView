@extends('layouts.frontend')

@section('title' ,'Banbelegung')

@section('content')

    <main id="main">
        <!-- ======= Services Section ======= -->
        <section id="services" class="services">
            <div class="container">
                <div class="section-title" data-aos="fade-in" data-aos-delay="50">
                    <h2>{{ $tableShow->ueberschrift }}</h2>
                    @if($victoCremonyTableShow == 1)
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Platz</th>
                                <th>Mannschaft</th>
                                <th>Punkte</th>
                                @if($tableShow->buchholzwertungaktiv)
                                    <th>Buchholzzahl</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tabeledataShows as $platzierung)
                                <tr>
                                    <td>{{ $platzierung->platz }}</td>
                                    <td>{{ $platzierung->getMannschaft->teamname }}</td>
                                    <td>{{ $platzierung->punkte }}</td>
                                    @if($tableShow->buchholzwertungaktiv)
                                    <td>{{ $platzierung->buchholzzahl }}</td
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Die Tabelle wird erst nach der Siegerehrung bekannt gegeben.</p>
                    @endif


                </div>
            </div>
        </section><!-- End Services Section -->
    </main><!-- End #main -->

@endsection
