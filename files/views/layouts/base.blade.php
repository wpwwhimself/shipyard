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
        {!! \App\ShipyardTheme::getFontImportUrl() !!}

        :root {
            {!! \App\ShipyardTheme::getColors() !!}
            {!! \App\ShipyardTheme::getGhostColors() !!}
            {!! \App\ShipyardTheme::getFonts() !!}
        }

        :root {
            @if (setting("app_adaptive_dark_mode"))
            color-scheme: light dark;
            @else
            color-scheme: light;
            &:has(body.dark) {
                color-scheme: dark;
            }
            @endif
        }

        @if (setting("app_adaptive_dark_mode"))
        @media (prefers-color-scheme: dark) {
            .icon.invert-when-dark {
                filter: invert(1);
            }
        }
        @endif
        </style>

        @env ("local")
        @if (file_exists(public_path("css/shipyard_theme_cache.css")))
        <link rel="stylesheet" href="{{ asset("css/shipyard_theme_cache.css") }}">
        @endif
        @else
        <link rel="stylesheet" href="https://wpww.pl/shipyard/{{ \App\ShipyardTheme::getTheme() }}.css">
        @endenv
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
