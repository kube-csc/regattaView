@extends('layouts.presentation')

@section('title', 'Livestream')

@section('head')
    <script>
        // Nach 2 Minuten (120000 ms) erste Prüfung,
        // danach alle 30 Sekunden (30000 ms) erneut prüfen
        setTimeout(function checkLive() {
            fetch("{{ route('presentation.checkLiveStream') }}")
                .then(response => response.json())
                .then(data => {
                    if (!data.active) {
                        window.location.href = "{{ route('presentation.welcome') }}";
                    } else {
                        // Wiederhole die Prüfung alle 15 Sekunden
                        setTimeout(checkLive, 15000); // 15 Sekunden
                    }
                });
        }, 105000); // 1 Minute und 45 Sekunden
        // Keine automatische Weiterleitung oder Seitenreload!
    </script>
@endsection

@section('content')
    <div class="position-fixed top-0 start-0 w-100 h-100 bg-black" style="z-index:9999;">
        <iframe
            class="w-100 h-100 border-0"
            src="{{ $liveStreamUrl }}"
            title="Livestream"
            allow="autoplay; encrypted-media"
            allowfullscreen>
        </iframe>
    </div>
@endsection
