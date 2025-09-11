@props([
    "model",
    "field",
])

<span class="field-value">
    <span role="icon" {{ Popper::pop($model::fields()[$field]['label']) }}>
        <x-shipyard.app.icon :name="$model::fields()[$field]['icon']" />
    </span>
    <span role="value">
        {{ $slot }}
    </span>
</span>
