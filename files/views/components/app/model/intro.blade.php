@props([
    "model" => null,
    "meta" => null,
])

@php
$meta = $meta ?? $model::META;
@endphp

<x-h :icon="$meta['icon']">{{ $meta['label'] }}</x-h>

<p>{{ $meta['description'] }}</p>
