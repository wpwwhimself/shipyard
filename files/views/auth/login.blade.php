@extends("layouts.shipyard.admin")
@section("title", "Logowanie")

@section("content")

<x-shipyard.app.form
    :action="route('process-login')"
    method="post"
    class="tight"
>
    <x-shipyard.ui.input type="text"
        name="name" label="Login"
        icon="user"
        required
    />
    <x-shipyard.ui.input type="password"
        name="password" label="Hasło"
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
