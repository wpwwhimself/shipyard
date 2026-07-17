@extends("layouts.shipyard.base")

@section("body")

<x-shipyard.app.big.header>
    <x-slot:top>
        <x-shipyard.app.logo />
        <x-shipyard.app.page-title>
            <x-slot:title>@yield("title", "Strona główna")</x-slot:title>
            <x-slot:subtitle>@yield("subtitle", setting("app_name"))</x-slot:subtitle>
        </x-shipyard.app.page-title>
    </x-slot:top>

    <x-slot:bottom>
        @env("local") <span @popper(Środowisko lokalne) class="accent danger"><x-shipyard.app.icon name="shovel" /></span> @endenv
        @env("stage") <span @popper(Środowisko testowe (stage)) class="accent success"><x-shipyard.app.icon name="test-tube" /></span> @endenv
        <x-shipyard.app.big.nav />
    </x-slot:bottom>
</x-shipyard.app.big.header>

@includeIf("components.layout-extra.background")

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

    <x-slot:middle>
        @includeIf("components.layout-extra.footer-extra")
    </x-slot:middle>

    <x-slot:bottom>
        @if (setting("contact_form_enabled") && \App\Models\User::all()->count(fn ($u) => $u->hasRole("mediator")))
        <x-shipyard.ui.button
            icon="email"
            pop="Formularz kontaktowy"
            action="none"
            onclick="openModal(`contact-form`)"
            class="tertiary"
        />
        @endif

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
