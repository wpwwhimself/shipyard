@extends("layouts.shipyard.admin")
@section("title", "Resetowanie hasła")

@section("content")

@php
$reset_own_password = !Auth::check() && !$user;
@endphp

<script>
function fillResetWithUsername(username) {
    ["password", "password_confirmation"].forEach((name) => {
        document.querySelector(`input[name="${name}"]`).value = username;
    });
}
</script>

<div class="card">
    <x-shipyard.app.form
        :action="route('password.reset.process')"
        method="post"
    >
        <p>
            Rozpoczynasz proces resetowania hasła
            @if ($reset_own_password)
            do swojego konta.
            @else
            dla użytkownika <strong>{{ $user->name }}</strong>.
            @endif
        </p>

        @switch (setting("users_password_reset_mode"))
            @case ("email")
                @if ($reset_own_password)
                <p>
                    Podaj swój adres email, na jaki ma zostać wysłany link do resetowania hasła.
                </p>
                <x-shipyard.ui.input type="email"
                name="email" label="Adres email"
                icon="at"
                required
                />
                @else
                <input type="hidden" name="email" value="{{ $user->email }}">
                <p>
                    Na adres email <strong>{{ $user->email }}</strong> zostanie wysłany link do resetowania hasła.
                </p>
                @endif
            @break

            @case ("manual")
                @if ($reset_own_password)
                <p>
                    Automatyczny reset haseł został wyłączony. Zgłoś się do administratora w celu zresetowania Twojego hasła.
                </p>

                @else
                <p>
                    Podaj nowe hasło dla użytkownika.
                </p>
                <x-shipyard.ui.button
                    :icon="model_icon('users')"
                    label="Podstaw nazwę użytkownika jako hasło"
                    action="none"
                    onclick="fillResetWithUsername('{{ $user->name }}');"
                    class="tertiary"
                />

                <input type="hidden" name="user_id" value="{{ $user->id }}">

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
                @endif
            @break
        @endswitch

        <x-slot:actions>
            @switch (setting("users_password_reset_mode"))
                @case ("email")
                <x-shipyard.ui.button
                    icon="send"
                    label="Wyślij link"
                    action="submit"
                    class="primary"
                />
                @break

                @case ("manual")
                @if (Auth::user()?->hasRole('technical'))
                <x-shipyard.ui.button
                    icon="check"
                    label="Zapisz"
                    action="submit"
                    class="primary"
                />
                @endif
                @break
            @endswitch
            <x-shipyard.ui.button
                icon="arrow-left"
                label="Wróć"
                :action="$reset_own_password ? route('login') : route('admin.model.edit', ['model' => 'users', 'id' => $user->id])"
            />
        </x-slot:actions>
    </x-shipyard.app.form>
</div>

@endsection
