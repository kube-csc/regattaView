@extends('layouts.speeker')

@section('content')

    <main id="main">
        <!-- ======= Search Section ======= -->
        <section id="search" class="search">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <form action="/Sprecher/Mannschaft/Auswahl" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="raceId" name="raceId" value="{{ $raceId }}">
                            <div class="search-box d-flex">
                                <select name="teamId" id="speekerId" class="form-control me-2">
                                    @foreach($teamsChoose as $teamChoose)
                                        <option value="{{ $teamChoose->id }}">{{ $teamChoose->teamname }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-secondary me-2 ml-2 ">auswahl</button>
                                <a href="/Sprecher/Mannschaft/{{ $teamId }}/{{ $raceId }}" class="me-2">
                                    <button type="button" class="btn btn-secondary ml-2">akuallisieren</button>
                                </a>
                                <a href="/Sprecher/">
                                    <button type="button" class="btn btn-secondary ml-2">Programmübersicht</button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- ======= Services Section ======= -->
        <section id="about" class="about">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="box">
                            <!-- Content for the first box -->
                            <h2>{{ $team->teamname }}</h2>
                            <p>
                                {!! $team->beschreibung !!}
                            </p>


                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box">
                            <!-- Content for the second box -->
                            @if($lanes!=Null)
                                @php
                                    $bahn=0;
                                @endphp
                                <h2>{{ $race->rennBezeichnung }}</h2>
                                <p>
                                    @if($race->status < 2)
                                        <br>Rennen noch nicht gesetzt<br><br>
                                    @endif
                                    @if($race->status == 2)
                                        @foreach($lanes as $lane)
                                            @php
                                                $bahn++;
                                            @endphp
                                            <label for="name">Bahn:</label>
                                            {{ $bahn}}
                                            @if($lane->mannschaft_id!=Null)
                                                {{ $lane->regattaTeam->teamname }}
                                                @if($lane->regattaTeam->beschreibung != Null)
                                                    <a href="/Sprecher/Mannschaft/{{ $lane->mannschaft_id }}/{{ $race->id }}" class="me-2">
                                                        <button type="button" class="btn btn-secondary ml-2">Info</button>
                                                    </a>
                                                @endif
                                            @endif
                                            <br>
                                        @endforeach
                                    @endif
                                    @if($race->status == 4)
                                        @foreach($lanes as $lane)
                                            @php
                                                $platz++;
                                            @endphp
                                            <label for="name">Platz:</label>
                                            {{ $platz }}
                                            @if($lane->mannschaft_id!=Null)
                                                {{ $lane->regattaTeam->teamname }}
                                                @if($lane->regattaTeam->beschreibung != Null)
                                                    <a href="/Sprecher/Mannschaft/{{ $lane->mannschaft_id }}/{{ $race->id }}" class="me-2">
                                                        <button type="button" class="btn btn-secondary ml-2">Info</button>
                                                    </a>
                                                @endif
                                            @endif
                                            <br>
                                        @endforeach
                                    @endif
                                  </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
