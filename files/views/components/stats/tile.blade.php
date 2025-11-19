@props([
    "label",
    "value" => null,
    "percentageOf" => null,
    "comparedTo" => null,
])

<div class="stat-tile">
    <span role="label subtitle">{{ $label }}</span>
    <span role="value">
        {{ $value ?? $slot }}

        @if ($comparedTo !== null)
        @php
        $diff = $value - $comparedTo;
        @endphp
        <small @class(["accent hot" => $diff > 0, "accent cold" => $diff < 0])>
            {{ $diff > 0 ? "+$diff" : $diff }}
        </small>
        @endif

        @if ($percentageOf !== null)
        @php
        $perc = round($value / $percentageOf * 100);
        @endphp
        <small>
            ({{ $perc }}%)
        </small>
        @endif
    </span>
</div>
