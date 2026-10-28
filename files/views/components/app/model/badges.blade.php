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
    <div {{ Popper::pop(implode("", [
        view("shipyard::components.app.model.badges.medal", ["badge" => $badge, "large" => true])->render(),
        $badge["label"],
    ])) }}>
        <x-shipyard::app.model.badges.medal :badge="$badge" />
    </div>

    @else
    <span class="{{ $badge["class"] ?? null }}" style="{{ $badge["style"] ?? null }}" {{ Popper::pop($badge["label"]) }}>
        <x-shipyard::app.icon :name="$badge['icon']" />
    </span>
    @endif
    @endforeach
</span>
