<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Präsentation')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS (optional, für einfaches Styling) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/presentation.css') }}" rel="stylesheet">
    @if(Session::has('presentation_background_image'))
        <style>
            body {
                background: url("{{ Session::get('presentation_background_image') }}") no-repeat center center fixed;
                background-size: cover;
            }
            .slide-container {
                background: rgba(0, 0, 0, 0.7);
            }
        </style>
    @endif
    @yield('head')
</head>
<body>
    <div class="slide-container">
        <div class="slide">
            @yield('content')
        </div>
    </div>
    <div class="slide-nav">
        @yield('navigation')
    </div>
    <!-- Bootstrap JS (optional, für Buttons etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
