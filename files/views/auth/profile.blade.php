@extends("layouts.shipyard.admin")
@section("title", "Mój profil")

@section("content")

<x-shipyard.app.card
    title="Dane użytkownika"
    icon="user"
>
    <p>Zalogowano jako {{ Auth::user()->name }}</p>
    
    <h3>Role w systemie</h3>
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
