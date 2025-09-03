@props([
    "badges" => [],
])

<div role="model-badges">
    @foreach ($badges as $badge)
    @if ($badge["condition"] ?? false) @continue @endif
    <span class="{{ $badge["class"] ?? null }}" {{ Popper::pop($badge["label"]) }}>
        @svg("mdi-".$badge["icon"])
    </span>
    @endforeach
</div>
