@props([
    "role",
])

<span role="role">
    @svg("mdi-".$role->icon)
    <strong>{{ $role->name }}</strong>:
    {{ $role->description }}
</span>
