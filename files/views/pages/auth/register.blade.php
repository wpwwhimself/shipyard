@extends("layouts.shipyard.admin")
@section("title", "Rejestracja")

@section("content")

<div class="card">
    <x-shipyard.app.form
        :action="route('register.process')"
        method="post"
        class="tight"
    >
        <x-shipyard.ui.input type="text"
            name="name" label="Login"
            icon="badge-account"
            required
        />
        <x-shipyard.ui.input type="text"
            name="email" label="Adres email"
            icon="at"
            required
        />

        <x-shipyard.ui.input type="checkbox"
            name="script-text" label="Akceptuję regulamin"
            icon="script-text"
            required
        />

        <x-slot:actions>
            <x-shipyard.ui.button
                icon="account-plus"
                label="Zarejestruj się"
                action="submit"
                class="primary"
            />
            <x-shipyard.ui.button
                icon="login"
                label="Mam już konto"
                :action="route('login')"
            />
        </x-slot:actions>
    </x-shipyard.app.form>
</div>

@endsection
