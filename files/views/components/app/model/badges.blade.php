@props([
    "badges" => [],
    "large" => false,
    "model" => null,
])

@php
if ($model?->badges) $badges = $model->badges;
@endphp

<span role="model-badges" @class([
    "large" => $large,
])>
    @foreach ($badges as $badge)
    @unless ($badge["condition"] ?? true) @continue @endunless

    @if ($badge["html"] ?? false)
    {{ $badge["html"] }}

    @elseif ($badge["medal"] ?? false)
    <div class="medal"
        {{ Popper::pop($badge["label"]) }}
        style="--medal-colors: {{ collect($badge['medal'])
            ->map(fn ($color, $offset) => [$color, $offset])
            ->sliding(2)
            ->map(fn ($cdata) => $cdata->values())
            ->map(fn ($cdata) => implode(', ', [
                implode(' ', [$cdata[0][0], $cdata[0][1].'%']),
                implode(' ', [$cdata[1][0], $cdata[0][1].'%'])
            ]))
            ->join(', ')
        }};"
    >
        <div class="medal-texture"></div>
    </div>

    @else
    <span class="{{ $badge["class"] ?? null }}" style="{{ $badge["style"] ?? null }}" {{ Popper::pop($badge["label"]) }}>
        <x-shipyard::app.icon :name="$badge['icon']" />
    </span>
    @endif
    @endforeach
</span>
