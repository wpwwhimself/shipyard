@props([
    "badges" => [],
])

<div role="model-badges">
    @foreach ($badges as $badge)
    @if ($badge["condition"] ?? false) @continue @endif

    @isset ($badge["html"])
    {{ $badge["html"] }}

    @else
    <span class="{{ $badge["class"] ?? null }}" style="{{ $badge["style"] ?? null }}" {{ Popper::pop($badge["label"]) }}>
        <x-shipyard.app.icon :name="$badge['icon']" />
    </span>
    
    @endisset
    @endforeach
</div>
