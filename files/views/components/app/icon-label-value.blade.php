@props([
    "icon",
    "label",
])

<span {{ $attributes->class("field-value") }}>
    <span role="icon" {{ Popper::pop($label) }}>
        <x-shipyard.app.icon :name="$icon" />
    </span>
    <span role="value">
        {{ $slot }}
    </span>
</span>
