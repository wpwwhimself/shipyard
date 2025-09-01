@props([
    "lvl" => 1,
    "icon" => null,
])

<h{{ $lvl }} {{ $attributes }}>
    @if ($icon) @svg("mdi-".$icon) @endif
    {{ $slot }}
</h{{ $lvl }}>
