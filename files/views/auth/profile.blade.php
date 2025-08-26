@extends("layouts.shipyard.admin")
@section("title", "Mój profil")

@section("content")

<x-shipyard.app.card
    title="Dane użytkownika"
    icon="user"
>
    <p>Login: {{ Auth::user()->name }}</p>
</x-shipyard.app.card>

@endsection
