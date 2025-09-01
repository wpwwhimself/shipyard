@props([
    "role",
])

<span role="role" {{ Popper::pop("<strong>$role->name</strong>: $role->description") }}>
    @svg("mdi-".$role->icon)
</span>
