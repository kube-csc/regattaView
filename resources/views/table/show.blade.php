@extends('layouts.frontend')

@section('title' ,'Tabelle '.$eventname)

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
                                    <th>Buchholzzahl <sup>*</sup></th>
                                @endif
                                <th>Rennanzahl</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tabeledataShows as $platzierung)
                                <tr>
                                    <td>{{ $platzierung->platz }}</td>
                                    <td>{{ $platzierung->getMannschaft->teamname }}</td>
                                    <td>{{ $platzierung->punkte }}</td>
                                    @if($tableShow->buchholzwertungaktiv)
                                    <td>{{ $platzierung->buchholzzahl }}</td>
                                    @endif
                                    <td>{{ $platzierung->rennanzahl }} von {{ $tableShow->maxrennen }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if($tableShow->buchholzwertungaktiv)
                            <small class="text-muted d-block mt-2">
                                <sup>*</sup> Die Buchholzzahl ist eine Feinwertung, bei der die Punkte aller Gegner, gegen die eine Mannschaft gespielt hat, aufsummiert werden. Sie dient dazu, bei Punktgleichheit die Platzierung zu bestimmen.
                            </small>
                        @endif
                        @if($pointsystems)
                            <div class="table-points-small mt-2">
                                <h2>Punktesystem</h2>
                                 <small class="text-muted d-block mt-2">
                                    Die Tabelle zeigt, wie viele Punkte für die jeweilige Platzierung vergeben werden. Das Punktesystem legt fest, wie die Endwertung berechnet wird.
                                 </small>
                                 <table class="table table-striped table-bordered mb-0">
                                    <thead>
                                    <tr>
                                        <th>Platz</th>
                                        <th>Punkte</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($pointsystems as $pointsystem)
                                          <tr>
                                            <td>{{ $pointsystem->platz }}</td>
                                            <td>{{ $pointsystem->punkte }}</td>
                                          </tr>
                                       @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @else
                        <p>Die Tabelle wird erst nach der Siegerehrung bekannt gegeben.</p>
                    @endif

                </div>
            </div>
        </section><!-- End Services Section -->
    </main><!-- End #main -->

@endsection
