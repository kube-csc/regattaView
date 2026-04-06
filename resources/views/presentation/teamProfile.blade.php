@extends('layouts.presentation')

@section('title', 'Mannschaftssteckbrief')

@section('head')
    @if($team)
        @php
            $minTime = config('presentation.times.team_profile', 10); // Mindestzeit in Sekunden
            $charsPerSec = config('presentation.times.chars_per_sec', 40); // Pro 40 Zeichen 1 Sekunde zusätzlich
            $beschreibung = strip_tags($team->beschreibung ?? '');
            $extraTime = $beschreibung ? ceil(strlen($beschreibung) / $charsPerSec) : 0;
            $refreshTime = $minTime + $extraTime;
        @endphp
        <meta http-equiv="refresh" content="{{ $refreshTime }};url={{ $nextUrl }}">
    @endif
@endsection

@section('content')
    @if($team)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white text-center fs-2">
                <strong>{{ $team->teamname }}</strong>
            </div>
            <div class="card-body text-center bg-light">
                <h4 class="mb-2 text-primary">{{ $team->verein ?? '' }}</h4>
                @php
                    $rennklasse = $team->teamWertungsGruppe?->typ ?? '-';
                    $bootsklasse = $team->teamWertungsGruppe?->template?->typ ?? '-';
                @endphp
                @if($rennklasse === $bootsklasse)
                    <div class="mb-2"><strong>Rennklasse:</strong> <span class="text-secondary">{{ $rennklasse }}</span></div>
                @else
                    <div class="mb-2"><strong>Rennklasse:</strong> <span class="text-secondary">{{ $rennklasse }}</span></div>
                    <div class="mb-2"><strong>Bootsklasse:</strong> <span class="text-secondary">{{ $bootsklasse }}</span></div>
                @endif
                <div class="mb-2"><strong>Ort:</strong> <span class="text-secondary">{{ $team->ort ?? '-' }}</span></div>

                @if($participationCount > 0)
                    <div class="mb-2"><strong>Teilnahmen in dieser Bootsklasse:</strong> <span class="text-primary">{{ $participationCount }}</span></div>
                @endif

                @if($lastResults->count() > 0)
                    <div class="mt-3 mb-2">
                        <strong>Letzte Ergebnisse:</strong>
                        <div class="table-responsive mt-1">
                            <table class="table table-sm table-bordered table-striped bg-white w-auto mx-auto">
                                <thead class="table-secondary">
                                    <tr>
                                        <th class="text-center">Platz</th>
                                        <th class="text-center">Rennen</th>
                                        <th class="text-center">Datum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lastResults as $res)
                                        <tr>
                                            <td class="text-end fw-bold text-primary">Platz {{ $res->platz ?? '-' }}</td>
                                            <td class="text-start">{{ $res->race->rennBezeichnung ?? 'Rennen' }}</td>
                                            <td class="text-start text-muted small">{{ $res->race->rennDatum ? date('d.m.Y', strtotime($res->race->rennDatum)) : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($team->beschreibung)
                    <div class="mb-3"><strong>Beschreibung:</strong><br>{!! $team->beschreibung !!}</div>
                @endif
                @if($team->bild)
                    <img src="{{ config('app.regatta_url') . '/storage/teamImage/' . $team->bild }}" alt="Teamfoto" class="img-fluid mb-3 rounded shadow" style="max-height:250px;">
                @endif
            </div>
        </div>
        <div class="mt-3 w-100">
            <div class="text-center bg-primary text-white rounded py-1 px-2 fw-semibold shadow-sm w-100">
                Mannschaft {{ $teamIndex+1 }} von {{ $teamCount }}
            </div>
        </div>
    @else
        <div class="alert alert-warning">Keine Mannschaften vorhanden.</div>
    @endif
@endsection
