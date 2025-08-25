@extends("layouts.shipyard.admin")

@section("content")

<x-shipyard.app.form
    :action="route('process-login')"
    method="post"
    class="tight"
>
    <x-shipyard.ui.input
        name="email"
        type="email"
        label="Email"
        icon="user"
        required
    />
    <x-shipyard.ui.input
        name="password"
        type="password"
        label="Hasło"
        icon="key"
        required
    />

    <x-slot:actions>
        <x-shipyard.ui.button
            icon="right-to-bracket"
            label="Zaloguj się"
            action="submit"
            class="primary"
        />
        <x-shipyard.ui.button
            icon="user-plus"
            label="Rejestracja"
            :action="route('register')"
        />
    </x-slot:actions>
</x-shipyard.app.form>

@endsection
