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

@php
    // ToDo: Funktion anderes Integrieren
    function textmax(&$beschreibung,$sollang,&$abgeschnitten)
    {
     $abgeschnitten=0;
     $laenge=strlen($beschreibung);
     if ($laenge>$sollang)
      {
        $beschreibung=substr($beschreibung,0,$sollang);
        $beschreibung=$beschreibung."...";  // ToDo:  Punkte werden nicht angefügt
        $abgeschnitten=1;
      }
    }
@endphp
