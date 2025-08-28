@props([
    "badges" => [],
])

<div role="model-badges">
    @foreach ($badges as $badge)
    @if ($badge["condition"] ?? false) @continue @endif
    <i class="fas fa-{{ $badge["icon"] }} {{ $badge["class"] ?? null }}" {{ Popper::pop($badge["label"]) }}></i>
    @endforeach
</div>
