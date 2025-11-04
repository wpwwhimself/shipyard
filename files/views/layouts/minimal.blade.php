@extends("layouts.shipyard.base")

@section("body")

<div id="corner-logo">
    <x-shipyard.app.logo />
    @env("local") <span @popper(Środowisko lokalne) class="accent danger"><x-shipyard.app.icon name="shovel" /></span> @endenv
    @env("stage") <span @popper(Środowisko testowe (stage)) class="accent success"><x-shipyard.app.icon name="test-tube" /></span> @endenv
</div>

<div id="middle-wrapper">
    @hasSection("sidebar")
    <aside>
        @yield("sidebar")
    </aside>
    @endif

    @hasSection("content")
    <main>
        @yield("content")
    </main>
    @endif
</div>

<x-shipyard.app.big.footer>
    <x-slot:top>
        <x-shipyard.auth.user-badge />
    </x-slot:top>

    <x-slot:bottom>
        @unless (setting("app_adaptive_dark_mode"))
        <x-shipyard.ui.button
            icon="theme-light-dark"
            pop="Tryb ciemny"
            action="none"
            onclick="toggleTheme()"
            class="tertiary"
        />
        @endunless

        <x-shipyard.app.app-badge />
    </x-slot:bottom>
</x-shipyard.app.big.footer>

@endsection()
