@extends('layouts.presentation')

@section('title', 'Mannschaftssteckbrief')

@section('head')
    @if($team)
        <meta http-equiv="refresh" content="10;url={{ $nextUrl }}">
    @endif
@endsection

@section('content')
    @if($team)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white text-center">
                <strong class="fs-2">{{ $team->teamname }}</strong>
            </div>
            <div class="card-body text-center bg-light">
                <h4 class="mb-2 text-primary">{{ $team->verein ?? '' }}</h4>
                <div class="mb-2"><strong>Ort:</strong> <span class="text-secondary">{{ $team->ort ?? '-' }}</span></div>
                @if($team->beschreibung)
                    <div class="mb-3"><strong>Beschreibung:</strong><br>{!! $team->beschreibung !!}</div>
                @endif
                @if($team->bild)
                    <img src="{{ env('REGATTA_URL') . '/storage/teamImage/' . $team->bild }}" alt="Teamfoto" class="img-fluid mb-3 rounded shadow" style="max-height:250px;">
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
