<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield("title", "Strona główna")
            @hasSection("subtitle")
            | @yield("subtitle")
            @endif
            | {{ setting("app_name") }}
        </title>
        <link rel="icon" href="{{ setting("app_favicon_path") ?? setting("app_logo_path") }}">

        {{-- 💄 styles 💄 --}}
        <style>
        {!! file_get_contents(public_path("css/identity.css")) !!}
        :root {
            --primary: light-dark({{ setting("app_accent_color_1_light") }}, {{ setting("app_accent_color_1_dark") }});
            --secondary: light-dark({{ setting("app_accent_color_2_light") }}, {{ setting("app_accent_color_2_dark") }});
            --tertiary: light-dark({{ setting("app_accent_color_3_light") }}, {{ setting("app_accent_color_3_dark") }});
            --primary-ghost: light-dark({{ setting("app_accent_color_1_light") }}77, {{ setting("app_accent_color_1_dark") }}77);
            --secondary-ghost: light-dark({{ setting("app_accent_color_2_light") }}77, {{ setting("app_accent_color_2_dark") }}77);
            --tertiary-ghost: light-dark({{ setting("app_accent_color_3_light") }}77, {{ setting("app_accent_color_3_dark") }}77);
        }
        #app-badge {
            & h2 {
                margin: 0;
            }
        }
        body {
            color-scheme: light dark;
            font-family: var(--base-font);
        }
        .logo {
            height: 5em;
        }
        table {
            border-spacing: 1em;

            & td,
            & th {
                text-align: left;
                vertical-align: middle;
            }
        }
        </style>

        @hasSection("prepends")
        @yield("prepends")
        @endif

        @csrf
    </head>
    <body>
        <div id="main-wrapper">
            @yield("body")
        </div>

        @hasSection("appends")
        @yield("appends")
        @endif
    </body>
</html>
