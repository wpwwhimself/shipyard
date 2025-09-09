@props([
    "role",
])

<span role="role" {{ Popper::pop("<strong>$role->name</strong>: $role->description") }}>
    <x-shipyard.app.icon :name="$role->icon" />
</span>
