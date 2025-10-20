@props([
    "model",
    "fieldName",
    "dummy" => false,
])

@php
$fdata = $model::getFields()[$fieldName];
@endphp

<x-shipyard.ui.input :type="($dummy ? 'dummy-' : '') . $fdata['type']"
    :name="$fieldName"
    :label="$fdata['label']"
    :icon="$fdata['icon']"
    :value="$fdata['type'] == 'checkbox' ? 1 : $model?->{$fieldName}"
    :checked="$fdata['type'] == 'checkbox' && $model?->{$fieldName}"
    :select-data="$fdata['select-data'] ?? null"
    :required="$fdata['required'] ?? false"
    :placeholder="$fdata['placeholder'] ?? null"
    :hint="$fdata['hint'] ?? null"
    :column-types="$fdata['column-types'] ?? null"
    :autofill-from="$fdata['autofill-from'] ?? null"
    :character-limit="$fdata['character-limit'] ?? null"
    :disabled="$fdata['disabled'] ?? false"
    :min="$fdata['min'] ?? null"
    :max="$fdata['max'] ?? null"
    :step="$fdata['step'] ?? null"
    {{ $attributes }}
/>
