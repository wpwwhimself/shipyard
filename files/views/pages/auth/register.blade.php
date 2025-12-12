@extends("layouts.shipyard.admin")
@section("title", "Rejestracja")

@section("content")

<div class="card">
    <x-shipyard.app.form
        :action="route('register.process')"
        method="post"
        class="tight"
        onsubmit="testRecaptcha(event);"
    >
        <x-shipyard.app.h lvl="3" :icon="model_icon('users')">Dane użytkownika</x-shipyard.app.h>
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

        @if (setting("users_terms_and_conditions_page_url"))
        <x-shipyard.app.h lvl="3" icon="script-text">Regulamin</x-shipyard.app.h>
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

        @if (setting("users_recaptcha_site_key"))
        <input type="hidden" name="g-recaptcha-response">
        @endif

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

@if (setting("users_recaptcha_site_key"))
<script src="https://www.google.com/recaptcha/api.js?render={{ setting('users_recaptcha_site_key') }}"></script>
<script>
function testRecaptcha(ev) {
    ev.preventDefault();
    grecaptcha.ready(function() {
        grecaptcha.execute('{{ setting('users_recaptcha_site_key') }}', {action: 'submit'})
            .then(function(token) {
                document.querySelector('input[name="g-recaptcha-response"]').value = token;
                ev.target.submit();
            });
    });
}
</script>
@endif

@endsection
