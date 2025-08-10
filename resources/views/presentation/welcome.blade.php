@extends('layouts.presentation')

@section('title', 'Willkommen')

@section('head')
    <meta http-equiv="refresh" content="10;url={{ route('presentation.information') }}">
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header bg-primary text-white text-center">
            <strong class="fs-2">{{ $event?->ueberschrift ?? 'Willkommen zur Präsentation' }}</strong>
        </div>
        <div class="card-body text-center bg-light">
            <p>{!!  $event?->beschreibung ?? $event?->nachtermin !!}</p>
        </div>
    </div>
@endsection
