@extends("layouts.shipyard.admin")
@section("title", "Mój profil")

@section("content")

<x-shipyard.app.card
    title="Dane użytkownika"
    :icon="model_icon('users')"
>
    <p>Zalogowano jako {{ Auth::user()->name }}</p>

    <x-shipyard.app.h lvl="3" :icon="model_icon('roles')">Role w systemie</x-shipyard.app.h>
    <ul>
        @foreach (Auth::user()->roles as $role)
        <li><x-shipyard.app.role.full :role="$role" /></li>
        @endforeach
    </ul>

    <x-slot:actions>
        <x-shipyard.ui.button
            icon="pencil"
            label="Edytuj"
            :action="route('admin.model.edit', ['model' => 'user', 'id' => Auth::user()->id])"
        />
    </x-slot:actions>
</x-shipyard.app.card>

@endsection
