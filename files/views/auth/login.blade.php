@extends("layouts.shipyard.admin")

@section("content")

<form action="{{ route('process-login') }}" method="post">
    @csrf
    
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

    <x-shipyard.ui.button
        icon="right-to-bracket"
        label="Zaloguj się"
        action="submit"
    />
</form>

@endsection
