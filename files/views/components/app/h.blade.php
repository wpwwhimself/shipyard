@props([
    "lvl" => 1,
    "icon" => null,
])

<h{{ $lvl }} {{ $attributes }}>
    @if ($icon) <x-shipyard.app.icon :name="$icon" /> @endif
    {{ $slot }}
</h{{ $lvl }}>
