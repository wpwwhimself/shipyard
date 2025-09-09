@props([
    "badges" => [],
])

<div role="model-badges">
    @foreach ($badges as $badge)
    @if ($badge["condition"] ?? false) @continue @endif
    <span class="{{ $badge["class"] ?? null }}" {{ Popper::pop($badge["label"]) }}>
        <x-shipyard.app.icon :name="$badge['icon']" />
    </span>
    @endforeach
</div>
