@extends('layouts.presentation')

@section('title', 'Video')

@section('head')
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
            title="Einspieler Video"
            allow="autoplay; encrypted-media"
            allowfullscreen>
        </iframe>
    </div>
@endsection
