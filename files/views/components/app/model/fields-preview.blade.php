@props([
    "fields" => [],
    "model",
])

@php
$data = $model::getFields();
@endphp

<div>
    @foreach ($fields as $field_name)
    <div>
        <span {{ Popper::pop($data[$field_name]["label"]) }}>
            <x-shipyard.app.icon :name="$data[$field_name]['icon']" />
        </span>
        <span>{!! $model->{$field_name."_pretty"} ?? $model->{$field_name} ?? "—" !!}</span>
    </div>
    @endforeach
</div>
