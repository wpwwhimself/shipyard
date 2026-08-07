@props([
    "clickable" => true,
    "forceTheme" => null,
])

@if (setting("app_logo_path") !== null)
@if ($clickable) <a href="/"> @endif

<picture class="logo-container">
    @if (setting("app_adaptive_dark_mode") && !$forceTheme)
    <source
        @class(["logo"])
        srcset="{{ asset(setting("app_logo_path")) }}"
        media="(prefers-color-scheme: light)"
    />
    @if (setting("app_logo_dark_path") !== null)
    <source
        @class(["logo"])
        srcset="{{ asset(setting("app_logo_dark_path")) }}"
        media="(prefers-color-scheme: dark)"
    />
    @endif
    @endif

    <img
        @class([
            "logo",
            "light" => !$forceTheme,
            "dark" => !$forceTheme && setting("app_logo_dark_path") === null,
            "hidden" => $forceTheme === "dark" && setting("app_logo_dark_path") !== null,
        ])
        src="{{ asset(setting("app_logo_path")) }}"
        alt="{{ setting("app_name") }}"
    >
    @if (setting("app_logo_dark_path") !== null)
    <img
        @class([
            "logo",
            "dark" => !$forceTheme,
            "hidden" => $forceTheme === "light",
        ])
        src="{{ asset(setting("app_logo_dark_path")) }}"
        alt="{{ setting("app_name") }}"
    >
    @endif
</picture>

@if ($clickable) </a> @endif
@endif
