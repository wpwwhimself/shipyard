@extends("layouts.shipyard.admin")
@section("title", "Resetowanie hasła")

@section("content")

<div class="card">
    <x-shipyard.app.form
        :action="route('password.reset.process')"
        method="post"
    >
        @php
        $reset_own_password = !Auth::check() && !$user;
        @endphp

        <p>
            Rozpoczynasz proces resetowania hasła
            @if ($reset_own_password)
            do swojego konta.
            @else
            dla użytkownika <strong>{{ $user->name }}</strong>.
            @endif
        </p>
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

        <x-slot:actions>
            <x-shipyard.ui.button
                icon="send"
                label="Wyślij link"
                action="submit"
                class="primary"
            />
            <x-shipyard.ui.button
                icon="arrow-left"
                label="Wróć"
                :action="$reset_own_password ? route('login') : route('admin.model.edit', ['model' => 'users', 'id' => $user->id])"
            />
        </x-slot:actions>
    </x-shipyard.app.form>
</div>

@endsection
