@props([
    "lvl" => 1,
    "icon" => null,
])

<h{{ $lvl }} {{ $attributes }}>
    @if ($icon) <i class="fas fa-{{ $icon }}"></i> @endif
    {{ $slot }}
</h{{ $lvl }}>
