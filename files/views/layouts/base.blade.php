<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield("title", "Strona główna")
            @hasSection("subtitle")
            | @yield("subtitle")
            @endif
            | {{ setting("app_name") }}
        </title>
        <link rel="icon" href="{{ setting("app_favicon_path") ?? setting("app_logo_path") }}">

        {{-- 💄 styles 💄 --}}
        <style>
        @import url("{{ \App\ShipyardTheme::FONT_IMPORT_URL }}");

        :root {
            @foreach (\App\ShipyardTheme::FONTS as $type => $fonts)
            --{{ $type }}-font: {!! implode(", ", array_map(fn ($f) => "\"$f\"", $fonts)) !!};
            @endforeach

            @foreach (\App\ShipyardTheme::getColors() as $name => $color)
            --{{ $name }}: {!! $color !!};
            @endforeach
            @foreach (\App\ShipyardTheme::getGhostColors() as $name => $color)
            --{{ $name }}-ghost: {!! $color !!};
            @endforeach
        }

        body {
            @if (setting("app_adaptive_dark_mode"))
            color-scheme: light dark;
            @else
            color-scheme: light;
            &.dark {
                color-scheme: dark;
            }
            @endif
        }
        </style>
        @if (file_exists(public_path("css/shipyard_theme_cache.css")))
        <link rel="stylesheet" href="{{ asset("css/shipyard_theme_cache.css") }}">
        @else
        <style>
        #theme-loader {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 9999;

            background: black;
            color: white;
            font-family: var(--heading-font);

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;

            & > img {
                height: 5em;
            }
        }
        </style>
        <style id="shipyard-styles" type="text/x-scss">
{!! file_get_contents(public_path("css/Shipyard/_base.scss")) !!}
{!! file_get_contents(public_path("css/Shipyard/".\App\ShipyardTheme::THEME.".scss")) !!}
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sass.js/0.11.1/sass.sync.min.js"></script>
        @endif
        <link rel="stylesheet" href="{{ asset("css/app.css") }}">
        {{-- 💄 styles 💄 --}}

        {{-- 🚀 standard scripts 🚀 --}}
        <script src="{{ asset("js/Shipyard/earlies.js") }}"></script>
        <script src="{{ asset("js/earlies.js") }}"></script>
        <script defer src="{{ asset("js/Shipyard/app.js") }}"></script>
        <script defer src="{{ asset("js/app.js") }}"></script>
        {{-- 🚀 standard scripts 🚀 --}}

        {{-- ✏️ ckeditor stuff ✏️ --}}
        <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.0.0/"
            }
        }
        </script>
        <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css">
        <link rel="stylesheet" href="{{ asset("css/Shipyard/ckeditor.css") }}">
        <script type="module" src="{{ asset("js/Shipyard/ckeditor.js") }}"></script>
        {{-- ✏️ ckeditor stuff ✏️ --}}

        {{-- ✅ choices stuff ✅ --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        {{-- ✅ choices stuff ✅ --}}

        @hasSection("prepends")
        @yield("prepends")
        @endif
    </head>
    <body>
        <div id="theme-loader">
            @if (setting("app_logo_path"))
            <img src="{{ asset(setting("app_logo_path")) }}" alt="{{ setting("app_name") }}">
            @endif
            Poczekaj, wczytujemy wygląd strony...
        </div>

        @if (!setting("app_adaptive_dark_mode"))
        <script>
        if (localStorage.getItem("theme") == "dark") toggleTheme()
        </script>
        @endif

        <div id="main-wrapper">
            @yield("body")
        </div>

        @hasSection("appends")
        @yield("appends")
        @endif

        <x-shipyard.app.toast />
        <x-shipyard.app.modal />

        @include("popper::assets")
    </body>
</html>
