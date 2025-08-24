@extends('layouts.presentation')

@section('title', 'Bahnaufstellungen')

@php
    // Es wird angenommen, dass $races bereits entsprechend gefiltert ist (nur level 1, falls keine Ergebnisse existieren)
    $raceIndex = request()->query('race', 0);
    $raceCount = count($races);
    $nextRaceIndex = $raceIndex + 1;
    $hasNext = $nextRaceIndex < $raceCount;
    $nextUrl = $hasNext
        ? route('presentation.laneOccupancy', ['race' => $nextRaceIndex])
        : route('presentation.result');
    $race = $races[$raceIndex] ?? null;
@endphp

@section('head')
    @if($race)
        <meta http-equiv="refresh" content="10;url={{ $nextUrl }}">
    @else
        <meta http-equiv="refresh" content="0;url={{ $nextUrl }}">
    @endif
@endsection

@section('content')
    @if($race)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong class="fs-2">Rennen {{ $race->nummer }}: {{ $race->rennBezeichnung }}</strong>
                <div class="mt-1">
                     <span class="badge bg-secondary">Abschnitt: {{ $race->level  }}</span>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="p-3">
                    <strong>Startzeit:</strong>
                    {{ \Carbon\Carbon::parse($race->rennDatum)->format('d.m.Y') }}
                    {{ \Carbon\Carbon::parse($race->rennUhrzeit)->format('H:i') }} Uhr
                    @php
                        $to   = explode(":" , $race->rennUhrzeit);
                        $from = explode(":" , $race->verspaetungUhrzeit);
                        $timto = $to[0]*60 + $to[1];
                        $timfrom = $from[0]*60 + $from[1];
                        $diff_in_minutes = $timfrom - $timto;
                        $diff_in_minutes =20;
                        $isToday = \Carbon\Carbon::parse($race->rennDatum)->isToday();
                    @endphp
                    @if($diff_in_minutes > 5 && $race->verspaetungUhrzeit && $isToday)
                        <br>
                        <strong>Voraussichtliche Startzeit:</strong>
                        {{ \Carbon\Carbon::parse($race->verspaetungUhrzeit)->format('H:i') }} Uhr
                    @endif
                </div>
                <table class="table table-striped mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-end" style="width:50%;">Bahn</th>
                            <th class="text-start" style="width:50%;">Team</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($race->lanes as $lane)
                            <tr>
                                <td class="text-end" style="width:50%;">{{ $lane->bahn }}</td>
                                <td class="text-start" style="width:50%;">
                                    @if($lane->regattaTeam)
                                        {{ $lane->regattaTeam->teamname }}
                                    @else
                                        Frei
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3 w-100">
            <div class="text-center bg-primary text-white rounded py-1 px-2 fw-semibold shadow-sm w-100">
                Rennen {{ $raceIndex+1 }} von {{ $raceCount }}
            </div>
        </div>
    @endif
@endsection
