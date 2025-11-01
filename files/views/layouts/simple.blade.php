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
        {!! \App\ShipyardTheme::getFontImportUrl() !!}

        :root {
            {!! \App\ShipyardTheme::getColors() !!}
            {!! \App\ShipyardTheme::getGhostColors() !!}
            {!! \App\ShipyardTheme::getFonts() !!}
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
