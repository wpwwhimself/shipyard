<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            @if (request()->path() == "/")
                {{ setting("metadata_title") }}
            @else
            @yield("title", "Strona główna")
            @hasSection("subtitle")
            | @yield("subtitle")
            @endif
            | {{ setting("app_name") }}
            @endif
        </title>
        <meta name="description" content="{{ setting("metadata_description") }}">
        <meta name="author" content="{{ setting("metadata_author") }}">
        <meta name="keywords" content="{{ setting("metadata_keywords") }}">
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ url()->current() }}" />
        <meta property="og:title" content="{{ setting("metadata_title") }}" />
        <meta property="og:image" content="{{ setting("metadata_image") }}" />
        <meta property="og:description" content="{{ setting("metadata_description") }}" />
        <link rel="icon" href="{{ setting("app_favicon_path") ?? setting("app_logo_path") }}">
        {{-- 🔍 seo 🔍 --}}

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
        <link rel="stylesheet" href="https://wpww.pl/shipyard/{{ \App\ShipyardTheme::getTheme() }}.css?v={{ shipyard_version() }}">
        @endenv
        <link rel="stylesheet" href="{{ asset("css/app.css") }}?v={{ shipyard_version() }}">
        {{-- 💄 styles 💄 --}}

        {{-- 🚀 standard scripts 🚀 --}}
        <script src="{{ asset("js/Shipyard/earlies.js") }}?v={{ shipyard_version() }}"></script>
        <script src="{{ asset("js/earlies.js") }}?v={{ shipyard_version() }}"></script>
        <script defer src="{{ asset("js/Shipyard/app.js") }}?v={{ shipyard_version() }}"></script>
        <script defer src="{{ asset("js/app.js") }}?v={{ shipyard_version() }}"></script>
        {{-- 🚀 standard scripts 🚀 --}}

        {{-- ✅ choices stuff ✅ --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        {{-- ✅ choices stuff ✅ --}}

        @if (\App\ShipyardTheme::moduleEnabled("wysiwyg"))
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
        <link rel="stylesheet" href="{{ asset("css/Shipyard/ckeditor.css") }}?v={{ shipyard_version() }}">
        <script type="module" src="{{ asset("js/Shipyard/ckeditor.js") }}?v={{ shipyard_version() }}"></script>
        {{-- ✏️ ckeditor stuff ✏️ --}}
        @endif

        @if (\App\ShipyardTheme::moduleEnabled("sheetmusic"))
        {{-- 🎼 abcjs stuff 🎼 --}}
        <link rel="stylesheet" href="{{ asset("css/Shipyard/abcjs.css") }}?v={{ shipyard_version() }}">
        <script src="https://cdn.jsdelivr.net/npm/abcjs/dist/abcjs-basic.min.js"></script>
        <script src="{{ asset("js/Shipyard/abcjs-note-transpose.js") }}?v={{ shipyard_version() }}"></script>
        {{-- 🎼 abcjs stuff 🎼 --}}
        @endif

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
