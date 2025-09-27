@props([
    "label",
])

<div class="stat-tile">
    <span role="label subtitle">{{ $label }}</span>
    <span role="value">{{ $slot }}</span>
</div>
