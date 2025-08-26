@if (setting("app_logo_path") !== null)
<a href="/">
    <img
        class="logo"
        src="{{ asset(setting("app_logo_path")) }}"
        alt="{{ setting("app_name") }}"
    >
</a>
@endif
