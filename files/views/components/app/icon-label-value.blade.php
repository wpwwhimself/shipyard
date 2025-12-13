@props([
    "icon" => null,
    "label" => null,
])

<span {{ $attributes->class("field-value") }}>
    @if ($icon)
    <span role="icon" {{ $label ? Popper::pop($label) : null }}>
        <x-shipyard.app.icon :name="$icon" />
    </span>
    @endif
    <span role="value">
        {{ $slot }}
    </span>
</span>
