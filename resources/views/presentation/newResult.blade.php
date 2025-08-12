@extends('layouts.presentation')

@section('head')
    <meta http-equiv="refresh" content="10;url={{ $redirectUrl }}">
@endsection

@section('content')
    @if($race)
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <strong class="fs-2">Neues Ergebnis – Rennen {{ $race->nummer }}: {{ $race->rennBezeichnung ?? $race->name }}</strong>
                <div class="mt-1">
                   <span class="badge bg-secondary">Abschnitt: {{ $race->level }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="p-3">
                    <strong>Startzeit:</strong>
                    {{ \Carbon\Carbon::parse($race->rennDatum)->format('d.m.Y') }}
                    {{ \Carbon\Carbon::parse($race->rennUhrzeit)->format('H:i') }} Uhr
                </div>
                <table class="table table-striped mb-0">
                    <thead class="table-success">
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
        <div class="mt-3 w-100">
            <div class="text-center bg-success text-white rounded py-1 px-2 fw-semibold shadow-sm w-100">
                Rennen 1 von 1
            </div>
        </div>
    @endif
@endsection
