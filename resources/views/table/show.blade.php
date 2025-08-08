@extends('layouts.frontend')

@section('title' ,'Tabelle '.$eventname)

@section('content')

<main id="main">
    <section id="services" class="services">
        <div class="container">
            <div class="section-title" data-aos="fade-in" data-aos-delay="50">
                <h2>{{ $tableShow->ueberschrift }}</h2>
                {{-- Filter-/Navigations-Buttons wie in index.blade.php --}}
                @php
                    $filterTeam = null;
                    if(session('team_filter')) {
                        $filterTeam = \App\Models\RegattaTeam::with('teamWertungsGruppe')->find(session('team_filter'));
                    }
                    $filterActive = session('team_filter_active', true);
                    $filterPossible = session('team_filter_possible', true);
                @endphp
                <div class="d-flex flex-column align-items-center mb-2">
                    @if($filterPossible && $filterTeam)
                        <div class="mb-1" style="font-size:0.8em;">
                            <span class="badge m-1"
                                  style="background-color: #ff9800; color: #fff; font-size: 0.85em; padding: 0.2em 0.6em;">
                                Gefiltert nach: {{ $filterTeam->teamname }}
                                @if(!$filterActive)
                                    [Filter aus]
                                @endif
                            </span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-center align-items-center mb-2 flex-wrap">
                        @if($filterPossible)
                            <a href="{{ route('program.selectTeamFilter') }}" class="me-2 mb-1">
                                <button type="button" class="btn btn-secondary rounded-lg m-1 btn-sm">
                                    Team filtern
                                </button>
                            </a>
                            @if($filterTeam)
                                <form method="POST" action="{{ route('program.setTeamFilter') }}" class="mb-1">
                                    @csrf
                                    <input type="hidden" name="toggle" value="1">
                                    <button type="submit"
                                            class="btn {{ $filterActive ? 'btn-warning' : 'btn-success' }} btn-sm rounded-lg m-1"
                                            style="font-size: 0.95em; padding: 0.3em 1em;">
                                        Filter {{ $filterActive ? 'aus' : 'an' }}
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="mb-3 d-flex flex-wrap justify-content-center">
                    <a href="/Programm">
                        <button type="button" class="btn btn-primary rounded-lg m-2">alle Rennen</button>
                    </a>
                    <a href="/Programm/geplante">
                        <button type="button" class="btn btn-primary rounded-lg m-2">geplante Rennen</button>
                    </a>
                    <a href="/Ergebnisse">
                        <button type="button" class="btn btn-primary rounded-lg m-2">gewertete Rennen</button>
                    </a>
                    <a href="/Tabellen">
                        <button type="button" class="btn btn-primary rounded-lg m-2">Tabellen</button>
                    </a>
                </div>
                @if($victoCremonyTableShow == 1)
                    {{-- Mobile-optimierte Darstellung: Immer Karten auf XS und SM, Tabelle erst ab md --}}
                    <div class="d-block d-sm-none">
                        @foreach($tabeledataShows as $platzierung)
                            <div class="card mb-3">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between" style="background-color: #e3f0ff;">
                                        <span class="fw-bold">Platz:</span>
                                        <span>{{ $platzierung->platz }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Team:</span>
                                        <span>{{ $platzierung->getMannschaft->teamname }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Punkte:</span>
                                        <span>{{ $platzierung->punkte }}</span>
                                    </div>
                                    @if($tableShow->buchholzwertungaktiv)
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold">Buchholzzahl:</span>
                                            <span>{{ $platzierung->buchholzzahl }}</span>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Absolvierte Rennen:</span>
                                        <span>{{ $platzierung->rennanzahl }} von {{ $tableShow->maxrennen }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- Ab sm (>=576px) Tabelle --}}
                    <div class="table-responsive d-none d-sm-block">
                        <div class="card mb-4">
                            <div class="card-body p-0">
                                <table class="table table-striped mb-0">
                                    <thead class="table-primary">
                                    <tr>
                                        <th>Platz</th>
                                        <th>Team</th>
                                        <th>Punkte</th>
                                        @if($tableShow->buchholzwertungaktiv)
                                            <th>Buchholzzahl <sup>*</sup></th>
                                        @endif
                                        <th>Absolvierte Rennen</th>
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
                            </div>
                        </div>
                    </div>
                    @if($tableShow->buchholzwertungaktiv)
                        <div class="alert alert-info py-2 px-3 mb-3">
                            <small class="text-muted">
                                <sup>*</sup> Die Buchholzzahl ist eine Feinwertung, bei der die Punkte aller Gegner, gegen die ein Team gespielt hat, aufsummiert werden. Sie dient dazu, bei Punktgleichheit die Platzierung zu bestimmen.
                            </small>
                        </div>
                    @endif
                    @if($pointsystems)
                        <div class="card mt-2">
                            <div class="card-header">
                                <h2 class="h5 mb-0">Punktesystem</h2>
                            </div>
                            <div class="card-body p-0">
                                <small class="text-muted d-block px-3 pt-2">
                                    Die Tabelle zeigt, wie viele Punkte für die jeweilige Platzierung vergeben werden. Das Punktesystem legt fest, wie die Endwertung berechnet wird.
                                </small>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered mb-0">
                                        <thead class="table-light">
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
                            </div>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning mt-4">
                        <p class="mb-0">Die Tabelle wird erst nach der Siegerehrung bekannt gegeben.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
</main>
@endsection
