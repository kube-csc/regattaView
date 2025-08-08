@extends('layouts.presentation')

@section('title', 'Ergebnisse')

@php
    $raceIndex = request()->query('race', 0);
    $raceCount = count($races);
    $nextRaceIndex = $raceIndex + 1;
    $hasNext = $nextRaceIndex < $raceCount;
    $nextUrl = $hasNext
        ? route('presentation.result', ['race' => $nextRaceIndex])
        : route('presentation.table');
    $race = $races[$raceIndex] ?? null;
@endphp

@section('head')
    @if($race)
        <meta http-equiv="refresh" content="10;url={{ $nextUrl }}">
    @endif
@endsection

@section('content')
    <h1>Ergebnisse</h1>
    @if($race)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong>Rennen {{ $race->nummer }}: {{ $race->rennBezeichnung }}</strong>
            </div>
            <div class="card-body p-0">
                <div class="p-3">
                    <strong>Startzeit:</strong>
                    {{ \Carbon\Carbon::parse($race->rennDatum)->format('d.m.Y') }}
                    {{ \Carbon\Carbon::parse($race->rennUhrzeit)->format('H:i') }} Uhr
                </div>
                <table class="table table-striped mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-end" style="width:45%;">Platz</th>
                            <th class="text-end" style="width:5%;">Bahn</th>
                            <th class="text-start" style="width:50%;">Team</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($race->lanes->sortBy('platz') as $lane)
                            @if(isset($lane->platz) && $lane->platz != 0)
                                <tr>
                                    <td class="text-end">
                                        {{ $lane->platz }}
                                    </td>
                                    <td class="text-end">{{ $lane->bahn }}</td>
                                    <td class="text-start">
                                        @if($lane->regattaTeam)
                                            {{ $lane->regattaTeam->teamname }}
                                        @else
                                            Frei
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="text-center mb-2 bg-dark text-white rounded py-1 px-2">
            <small>Rennen {{ $raceIndex+1 }} von {{ $raceCount }}</small>
        </div>
    @else
        <div class="alert alert-warning">Keine Ergebnisse vorhanden.</div>
    @endif
@endsection
