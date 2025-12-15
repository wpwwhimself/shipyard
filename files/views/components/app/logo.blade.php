@props([
    "clickable" => true,
])

@if (setting("app_logo_path") !== null)
@if ($clickable) <a href="/"> @endif
    <img
        class="logo"
        src="{{ asset(setting("app_logo_path")) }}"
        alt="{{ setting("app_name") }}"
    >
@if ($clickable) </a> @endif
@endif
