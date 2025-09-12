@props([
    "title" => null,
    "subtitle" => null,
    "icon" => null,
    "titleLvl" => 2,
])

<div {{ $attributes->class("card") }}>
    @if ($title || $subtitle || $icon)
    <div role="top-bar">
        <div class="title-wrapper">
            @if ($title)
            <x-shipyard.app.h :lvl="$titleLvl" role="card-title" :icon="$icon">{{ $title }}</x-shipyard.app.h>
            @endif
            @if ($subtitle)
            <x-shipyard.app.h :lvl="$titleLvl + 1" role="card-subtitle">{{ $subtitle }}</x-shipyard.app.h>
            @endif
        </div>

        @isset($actions)
        <div class="actions">
            {{ $actions }}
        </div>
        @endisset
    </div>
    @endif

    @isset ($slot)
    <div class="contents">
        {{ $slot }}
    </div>
    @endisset
</div>
