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
        <link rel="stylesheet" href="{{ asset("css/identity.css") }}">
        <style>
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
        <link rel="stylesheet" href="{{ asset("css/shipyard_theme_cache.css") }}?{{ time() }}">
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
:root {
    --primary: light-dark({{ setting("app_accent_color_1_light") }}, {{ setting("app_accent_color_1_dark") }});
    --secondary: light-dark({{ setting("app_accent_color_2_light") }}, {{ setting("app_accent_color_2_dark") }});
    --tertiary: light-dark({{ setting("app_accent_color_3_light") }}, {{ setting("app_accent_color_3_dark") }});
    --primary-ghost: light-dark({{ setting("app_accent_color_1_light") }}77, {{ setting("app_accent_color_1_dark") }}77);
    --secondary-ghost: light-dark({{ setting("app_accent_color_2_light") }}77, {{ setting("app_accent_color_2_dark") }}77);
    --tertiary-ghost: light-dark({{ setting("app_accent_color_3_light") }}77, {{ setting("app_accent_color_3_dark") }}77);
}
{!! file_get_contents(public_path("css/Shipyard/_base.scss")) !!}
{!! file_get_contents(public_path("css/Shipyard/".setting("app_theme").".scss")) !!}
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sass.js/0.11.1/sass.sync.min.js"></script>
        @endif
        <link rel="stylesheet" href="{{ asset("css/app.css") }}?{{ time() }}">
        {{-- 💄 styles 💄 --}}

        {{-- 🌳 environment distinguishing elements 🌳 --}}
        @env (["local", "stage"])
        <style>
        :root {
            @env ("local")
            --test-color: #0f0;
            @endenv
            @env ("stage")
            --test-color: #ff0;
            @endenv
        }
        </style>
        @endenv
        {{-- 🌳 environment distinguishing elements 🌳 --}}

        {{-- 🚀 standard scripts 🚀 --}}
        <script src="{{ asset("js/Shipyard/earlies.js") }}?{{ time() }}"></script>
        <script src="{{ asset("js/earlies.js") }}?{{ time() }}"></script>
        <script defer src="{{ asset("js/Shipyard/app.js") }}?{{ time() }}"></script>
        <script defer src="{{ asset("js/app.js") }}?{{ time() }}"></script>
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
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" /> --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script> --}}
        {{-- ✅ choices stuff ✅ --}}

        @hasSection("prepends")
        @yield("prepends")
        @endif

        @csrf
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
