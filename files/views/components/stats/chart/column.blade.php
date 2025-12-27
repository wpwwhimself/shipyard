@props([
    "title" => null,
    "subtitle" => null,
    "icon" => null,
    "data" => [],
])

@php
$maxValue = $data->max("value");
@endphp

<x-shipyard.stats.chart.wrapper
    :title="$title"
    :subtitle="$subtitle"
    :icon="$icon"
    class="column"
>
    @isset ($actions)
    <x-slot:actions>
        {{ $actions }}
    </x-slot:actions>
    @endisset

    @foreach ($data as $d)
    <div class="value-wrapper">
        <div class="value" style="height: {{ $d['value'] / $maxValue * 100 }}%;"
            @if ($d["value_label"] ?? null)
            {{ Popper::pop($d["value_label"]) }}
            @endif
        >
            <span class="value-label">{{ $d["value"] }}</span>
        </div>
    </div>
    <div class="label">
        {{ $d["label"] }}
    </div>
    @endforeach
</x-shipyard.stats.chart.wrapper>
