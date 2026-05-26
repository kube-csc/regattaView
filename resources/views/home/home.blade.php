@extends('layouts.headFrontend')

@php
    $titel="Event - ".env('VEREIN_NAME');
@endphp

@section('title' , $titel)

@section('content')

    <main id="main">

        @include('home.regatta')

        @include('home.ctaSection')

        @include('home.counts')

        <! -- include('home.testimonials') -->

        @include('home.team')

        @include('home.contakt')

    </main><!-- End #main -->

@endsection

