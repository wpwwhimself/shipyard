@props([
    "title" => null,
    "subtitle" => null,
    "icon" => null,
    "data" => [], // każdy element musi mieć "label" (string) i "value" (liczba). "value_label" wymusza wyświetlanie wartości po najechaniu
    "mode" => "normal", // normal (bez zmian) | monetary (formatowanie walutowe) | percentage (obliczanie procentów)
    "max" => null,
])

@php
$maxValue = max($max, collect($data)->max("value"));
$sumValue = collect($data)->sum("value");
@endphp

<x-shipyard::stats.chart.wrapper
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
    @php
    $value_pretty = $d["value"];
    if ($mode == "percentage") {
        $value_pretty = round($d["value"] / $sumValue * 100, 1) . "%";
        $d["value_label"] ??= $d["value"];
    } else if ($mode == "monetary") {
        $value_pretty = number_format(
            round($d["value"], 2),
            $mode == "monetary" ? 2 : 0,
            ",",
            "&nbsp;"
        );
    }

    @endphp
    <div class="value-wrapper">
        <div class="value" style="height: {{ $d['value'] / $maxValue * 100 }}%;"
            @if ($d["value_label"] ?? null)
            {{ Popper::pop($d["value_label"]) }}
            @endif
        >
            <span class="value-label">{!! $value_pretty !!}</span>
        </div>
    </div>
    <div class="label">
        {{ $d["label"] }}
    </div>
    @endforeach
</x-shipyard::stats.chart.wrapper>
