@props([
    "role",
])

@php
$role = \App\Scaffolds\Role::find($role);
@endphp

<span role="role">
    <x-shipyard.app.icon :name="$role['icon']" />
    <strong>{{ $role['name'] }}</strong>:
    {{ $role['description'] }}
</span>
