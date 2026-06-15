@props([
    "model",
    "field",
])

<x-shipyard.app.icon-label-value
    :icon="$model::getFields()[$field]['icon']"
    :label="$model::getFields()[$field]['label']"
    class="field-value"
>
    {{ $slot ?? $model->{$field} }}
</x-shipyard.app.icon-label-value>
