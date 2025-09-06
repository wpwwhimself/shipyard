@extends("layouts.shipyard.admin")
@section("title", "Zarządzanie hasłem")

@section("content")

<x-shipyard.app.card
    title="Zmiana hasła"
    icon="key-change"
>
    <x-shipyard.app.form :action="route('password.set.process')" method="POST">
        @if (request()->has("id"))
        <input type="hidden" name="user_id" value="{{ request()->get("id") }}">
        <x-shipyard.ui.input type="password"
            name="current_password" label="Obecne hasło"
            icon="key"
            required
        />
        @endif
        @isset($token)
        <input type="hidden" name="token" value="{{ $token }}">
        <x-shipyard.ui.input type="email"
            name="email" label="Adres email"
            icon="at"
            required
        />
        @endisset
        <x-shipyard.ui.input type="password"
            name="password" label="Nowe hasło"
            icon="key"
            required
        />
        <x-shipyard.ui.input type="password"
            name="password_confirmation" label="Powtórz nowe hasło"
            icon="key-link"
            required
        />

        <x-slot:actions>
            <x-shipyard.ui.button
                icon="content-save"
                label="Zapisz zmiany"
                class="primary"
                action="submit"
            />
            <x-shipyard.ui.button
                icon="arrow-left"
                label="Wróć"
                :action="route('profile')"
            />
        </x-slot:actions>
    </x-shipyard.app.form>
</x-shipyard.app.card>

@endsection
