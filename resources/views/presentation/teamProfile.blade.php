@extends('layouts.presentation')

@section('title', 'Mannschaftssteckbrief')

@section('head')
    @if($team)
        <meta http-equiv="refresh" content="10;url={{ $nextUrl }}">
    @endif
@endsection

@section('content')
    @if($team)
        <div class="card mx-auto mb-4" style="max-width: 600px;">
            <div class="card-header bg-primary text-white text-center">
                <h2 class="mb-0">{{ $team->teamname }}</h2>
                @if($team->teamWertungsGruppe)
                    <div class="mt-1" style="font-size:1.1em;">
                        <span class="badge bg-secondary">
                            {{ $team->teamWertungsGruppe->typ }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="card-body text-center">
                <h4 class="mb-2">{{ $team->verein ?? '' }}</h4>
                <div class="mb-2"><strong>Ort:</strong> {{ $team->ort ?? '-' }}</div>
                @if($team->beschreibung)
                    <div class="mb-3"><strong>Beschreibung:</strong><br>{!! $team->beschreibung !!}</div>
                @endif
                @if($team->bild)
                    <img src="{{ $team->bild }}" alt="Teamfoto" class="img-fluid mb-3" style="max-height:250px;">
                @endif
            </div>
        </div>
        <div class="text-center mb-2 bg-dark text-white rounded py-1 px-2">
            <small>
                Mannschaft {{ $teamIndex+1 }} von {{ $teamCount }}
            </small>
        </div>
    @else
        <div class="alert alert-warning">Keine Mannschaften vorhanden.</div>
    @endif
@endsection
