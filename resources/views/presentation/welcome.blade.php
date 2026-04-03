@extends('layouts.presentation')

@section('title', 'Willkommen')

@section('head')
    <meta http-equiv="refresh" content="{{ config('presentation.times.welcome', 8) }};url={{ route('presentation.information') }}">
@endsection

@section('content')
    <div class="w-100 px-0">
        <div class="card mb-4 border-primary shadow-sm rounded-0">
            <div class="card-header bg-primary text-white text-center fs-2">
                <h2 class="mb-0 text-white fw-bold">{{ $event?->ueberschrift ?? 'Willkommen zur Präsentation' }}</h2>
            </div>
            <div class="card-body text-center bg-light">
                <p>{!!  $event?->beschreibung ?? $event?->nachtermin !!}</p>
            </div>
        </div>
    </div>
@endsection
