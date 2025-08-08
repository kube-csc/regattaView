@extends('layouts.presentation')

@section('title', 'Willkommen')

@section('head')
    <meta http-equiv="refresh" content="10;url={{ route('presentation.information') }}">
@endsection

@section('content')
    <h1>{{ $event?->ueberschrift ?? 'Willkommen zur Präsentation' }}</h1>
    <p>{!!  $event?->beschreibung ?? $event?->nachtermin !!}</p>
@endsection
