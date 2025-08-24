@extends('layouts.presentation')

@section('title', 'Information')

@section('head')
    @if($info)
        @php
            $minTime = 10; // Mindestzeit in Sekunden
            $charsPerSec = 40; // Pro 40 Zeichen 1 Sekunde zusätzlich
            $beschreibung = strip_tags($info->informationBeschreibung ?? '');
            $extraTime = $beschreibung ? ceil(strlen($beschreibung) / $charsPerSec) : 0;
            $refreshTime = $minTime + $extraTime;
        @endphp
        <meta http-equiv="refresh" content="{{ $refreshTime }};url={{ $nextUrl }}">
    @else
        <meta http-equiv="refresh" content="0;url={{ route('presentation.teams') }}">
    @endif
@endsection

@section('content')
    @if($info)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white text-center fs-2">
                <strong>Regatta Information</strong>
            </div>
            <div class="card-body bg-light">
                <h3 class="mb-3 text-primary text-center">{{ $info->informationTittel }}</h3>
                {!! $info->informationBeschreibung !!}
            </div>
        </div>
        <div class="mt-3 w-100">
            <div class="text-center bg-primary text-white rounded py-1 px-2 fw-semibold shadow-sm w-100">
                Information {{ $infoIndex+1 }} von {{ $infoCount }}
            </div>
        </div>
    @endif
@endsection
