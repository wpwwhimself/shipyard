@props([
    "fields" => [],
    "model",
])

@php
$data = $model::getFields();
@endphp

<div>
    @foreach ($fields as $field_name)
    <x-shipyard.app.icon-label-value
        :icon="$data[$field_name]['icon']"
        :label="$data[$field_name]['label']"
    >
        {!! $model->{$field_name."_pretty"} ?? $model->{$field_name} ?? "—" !!}
    </x-shipyard.app.icon-label-value>
    @endforeach
</div>
