@props([
    "lvl" => 1,
    "icon" => null,
    "iconMode" => null,
    "iconData" => null,
])

<h{{ $lvl }} {{ $attributes }}>
    @if ($icon) <x-shipyard.app.icon :name="$icon" :mode="$iconMode" :data="$iconData" /> @endif
    {{ $slot }}
</h{{ $lvl }}>
