@extends("layouts.shipyard.base")

@section("body")

<x-shipyard.app.big.header>
    <x-slot:top>
        <x-shipyard.app.logo />
        <x-shipyard.app.page-title>
            <x-slot:title>@yield("title", "Strona główna")</x-slot:title>
            <x-slot:subtitle><a href="/">{{ setting("app_name") }}</a></x-slot:subtitle>
        </x-shipyard.app.page-title>
    </x-slot:top>

    <x-slot:bottom>
        <x-shipyard.app.big.nav />
    </x-slot:bottom>
</x-shipyard.app.big.header>

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
</div id="middle-wrapper">

<x-shipyard.app.big.footer>
    <x-slot:top>
        <x-shipyard.auth.user-badge />
    </x-slot:top>

    <x-slot:bottom>
        @unless (setting("app_adaptive_dark_mode"))
        <x-shipyard.ui.button
            icon="circle-half-stroke"
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
