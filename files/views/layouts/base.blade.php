<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield("title", "Strona główna") | {{ setting("app_name") }}</title>

        {{-- 💄 styles 💄 --}}
        <style id="shipyard-styles" type="text/x-scss">
{!! file_get_contents(public_path("css/identity.css")) !!}
:root {
    --primary: light-dark({{ setting("app_accent_color_1_light") }}, {{ setting("app_accent_color_1_dark") }});
    --secondary: light-dark({{ setting("app_accent_color_2_light") }}, {{ setting("app_accent_color_2_dark") }});
    --tertiary: light-dark({{ setting("app_accent_color_3_light") }}, {{ setting("app_accent_color_3_dark") }});
}
{!! file_get_contents(public_path("css/Shipyard/_base.scss")) !!}
{!! file_get_contents(public_path("css/Shipyard/".setting("app_theme").".scss")) !!}
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sass.js/0.11.1/sass.sync.min.js"></script>
        <link rel="stylesheet" href="{{ asset("css/app.css") }}">
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
        <script src="{{ asset("js/Shipyard/earlies.js") }}"></script>
        <script src="{{ asset("js/earlies.js") }}"></script>
        <script defer src="{{ asset("js/Shipyard/app.js") }}"></script>
        <script defer src="{{ asset("js/app.js") }}"></script>
        {{-- 🚀 standard scripts 🚀 --}}

        {{-- 🙂 icons 🙂 --}}
        <script src="https://kit.fontawesome.com/97bfe258ce.js" crossorigin="anonymous"></script>
        {{-- 🙂 icons 🙂 --}}

        {{-- ✏️ ckeditor stuff ✏️ --}}
        <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.0.0/"
            }
        }
        </script>
        {{-- <script type="module" src="{{ asset("js/ckeditor.js") }}?{{ time() }}"></script> --}}
        {{-- ✏️ ckeditor stuff ✏️ --}}

        @include("popper::assets")
    </head>
    <body>
        <div id="main-wrapper">
            @yield("body")
        </div>
    </body>
</html>
