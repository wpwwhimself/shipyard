@props([
    "clickable" => true,
])

@if (setting("app_logo_path") !== null)
@if ($clickable) <a href="/"> @endif

<picture role="logo">
    @if (setting("app_adaptive_dark_mode"))
    <source
        @class(["logo"])
        srcset="{{ asset(setting("app_logo_path")) }}"
        media="(prefers-color-scheme: light)"
    />
    <source
        @class(["logo"])
        srcset="{{ asset(setting("app_logo_dark_path")) }}"
        media="(prefers-color-scheme: dark)"
    />
    @endif
    <img
        @class(["logo"])
        src="{{ asset(setting("app_logo_path")) }}"
        alt="{{ setting("app_name") }}"
    >
    <img
        @class(["logo", "dark"])
        src="{{ asset(setting("app_logo_dark_path")) }}"
        alt="{{ setting("app_name") }}"
    >
</picture>

@if ($clickable) </a> @endif
@endif
