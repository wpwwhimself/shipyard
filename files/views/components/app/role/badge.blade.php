@props([
    "role",
])

<span role="role">
    <i class="fa-solid fa-{{ $role->icon }}" {{ Popper::pop("<strong>$role->name</strong>: $role->description") }}></i>
</span>
