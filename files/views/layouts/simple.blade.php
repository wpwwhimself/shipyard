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
