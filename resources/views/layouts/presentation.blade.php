<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Präsentation')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS (optional, für einfaches Styling) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/presentation.css') }}" rel="stylesheet">
    @if(config('presentation.options.show_background_image', 1) && Session::has('presentation_background_image'))
        <style>
            html,
            body {
                height: 100%;
                min-height: 100%;
            }

            body {
                margin: 0;
                min-height: 100vh;
                background-color: #222;
                background-image: url("{{ Session::get('presentation_background_image') }}");
                background-repeat: no-repeat;
                background-position: center center;
                background-attachment: scroll;
                background-size: cover;
            }

            @supports (height: 100dvh) {
                body {
                    min-height: 100dvh;
                }

                body {
                    background-size: cover;
                }
            }

            @media (min-width: 1024px) {
                body {
                    background-attachment: fixed;
                }
            }

            .slide-container {
                min-height: 100vh;
                background: rgba(0, 0, 0, 0.7);
            }

            @supports (height: 100dvh) {
                .slide-container {
                    min-height: 100dvh;
                }
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
