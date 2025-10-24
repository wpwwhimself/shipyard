@props([
    "badges" => [],
])

<span role="model-badges">
    @foreach ($badges as $badge)
    @unless ($badge["condition"] ?? true) @continue @endunless

    @isset ($badge["html"])
    {{ $badge["html"] }}

    @else
    <span class="{{ $badge["class"] ?? null }}" style="{{ $badge["style"] ?? null }}" {{ Popper::pop($badge["label"]) }}>
        <x-shipyard.app.icon :name="$badge['icon']" />
    </span>
    
    @endisset
    @endforeach
</span>
