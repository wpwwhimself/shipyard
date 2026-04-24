@extends("layouts.shipyard.admin")
@section("title", "Mój profil")

@section("content")

<div @class(["flex", "down", "stagger-contents" => setting("animations_mode") >= 1])>

<x-shipyard.app.section
    title="Dane użytkownika"
    :icon="model_icon('users')"
>
    <div @class(["flex", "down", "stagger-contents" => setting("animations_mode") >= 2])>
    <p>Zalogowano jako <strong class="accent primary">{{ Auth::user() }}</strong></p>

    <x-shipyard.app.h lvl="3" :icon="model_icon('roles')">Role w systemie</x-shipyard.app.h>
    <ul>
        @foreach (Auth::user()->roles as $i => $role)
        <li><x-shipyard.app.role.full :role="$role" /></li>
        @endforeach
    </ul>
    </div>

    <x-slot:actions>
        <x-shipyard.ui.button
            icon="pencil"
            label="Edytuj"
            :action="route('admin.model.edit', ['model' => 'users', 'id' => Auth::user()->id])"
        />
    </x-slot:actions>
</x-shipyard.app.section>

</div>

@endsection
