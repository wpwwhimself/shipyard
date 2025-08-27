@props([
    "role",
])

<span role="role">
    <i class="fa-solid fa-{{ $role->icon }}"></i>
    <strong>{{ $role->name }}</strong>:
    {{ $role->description }}
</span>
