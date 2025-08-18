@extends('layouts.presentation')

@section('title', 'Video')

@section('head')
    @php
        $videoOptions = include(resource_path('views/textimport/slideShow_options.php'));
        $videoUrl = $videoOptions['videoUrl'];
        $videoLaenge = !empty($videoOptions['videoLaenge']) ? $videoOptions['videoLaenge'] : 120000;
    @endphp
    <script>
        setTimeout(function() {
            window.location.href = "{{ route('presentation.welcome') }}";
        }, {{ $videoLaenge }}); // Redirect nach Video-Länge
    </script>
@endsection

@section('content')
    <div class="position-fixed top-0 start-0 w-100 h-100 bg-black" style="z-index:9999;">
        <iframe
            class="w-100 h-100 border-0"
            src="{{ $videoUrl }}"
            title="YouTube video"
            allow="autoplay; encrypted-media"
            allowfullscreen>
        </iframe>
    </div>
@endsection
