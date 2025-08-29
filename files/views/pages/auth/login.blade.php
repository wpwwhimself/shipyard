@extends("layouts.shipyard.admin")
@section("title", "Logowanie")

@section("content")

<div class="card">
    <x-shipyard.app.form
        :action="route('login.process')"
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
            @if (setting("users_self_register_enabled"))
            <x-shipyard.ui.button
                icon="user-plus"
                label="Rejestracja"
                :action="route('register')"
            />
            @endif
        </x-slot:actions>
    </x-shipyard.app.form>
</div>

@endsection
