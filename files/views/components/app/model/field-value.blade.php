@props([
    "model",
    "field",
])

<span {{ $attributes->class("field-value") }}>
    <span role="icon" {{ Popper::pop($model::getFields()[$field]['label']) }}>
        <x-shipyard.app.icon :name="$model::getFields()[$field]['icon']" />
    </span>
    <span role="value">
        {{ $slot ?? $model->{$field} }}
    </span>
</span>
