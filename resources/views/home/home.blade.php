@extends('layouts.headFrontend')

@php
    $titel="Event - ".env('VEREIN_NAME');
@endphp

@section('title' , $titel)

@section('content')

    @php
        $hasTable = (bool) ($hasTable ?? false);
    @endphp

    <main id="main">

        @include('home.regatta')

        @if($hasTable)
            @include('home.ctaSection')

            @include('home.counts')
        @endif

        <! -- include('home.testimonials') -->

        @include('home.team')

        @include('home.contakt')

    </main><!-- End #main -->

@endsection

