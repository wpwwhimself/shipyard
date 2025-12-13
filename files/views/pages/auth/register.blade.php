@extends("layouts.shipyard.admin")
@section("title", "Rejestracja")

@section("content")

<x-shipyard.app.card>
    <p>Rozpoczynasz proces rejestracji użytkownika w systemie.</p>
</x-shipyard.app.card>

<x-shipyard.app.form
    :action="route('register.process')"
    method="post"
>
    <x-shipyard.app.section title="Dane użytkownika" :icon="model_icon('users')">
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
    </x-shipyard.app.section>

    <x-shipyard.app.section title="Regulamin i warunki użytkowania" icon="script-text">
        @if (setting("users_terms_and_conditions_page_url"))
        <x-shipyard.ui.button
            icon="open-in-new"
            label="Regulamin jest dostępny tutaj"
            :action="setting('users_terms_and_conditions_page_url')"
            target="_blank"
        />
        <x-shipyard.ui.input type="checkbox"
            name="terms" label="Akceptuję regulamin"
            icon="signature"
            required
        />
        @endif

        <x-shipyard.ui.input type="text"
            name="test" :label="setting('users_turing_question')"
            icon="robot"
            hint="To pytanie jest częścią testu antyspamowego. Poprawna odpowiedź jest konieczna do dokończenia procesu."
        />
    </x-shipyard.app.section>

    <x-slot:actions>
        <x-shipyard.app.card>
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
        </x-shipyard.app.card>
    </x-slot:actions>
</x-shipyard.app.form>

@endsection
