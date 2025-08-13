@extends('layouts.presentation')

@section('title', 'Video')

@section('head')
    <script>
        setTimeout(function() {
            window.location.href = "{{ route('presentation.welcome') }}";
        }, 12000); // Temp:: Redirect after 120000    ms (2 minutes)
    </script>
@endsection

@section('content')
    <div class="position-fixed top-0 start-0 w-100 h-100 bg-black" style="z-index:9999;">
        <iframe
            class="w-100 h-100 border-0"
            src="https://www.youtube.com/embed/Gjf1a5btgcc?autoplay=1&mute=1"
            title="YouTube video"
            allow="autoplay; encrypted-media"
            allowfullscreen>
        </iframe>
    </div>
@endsection
