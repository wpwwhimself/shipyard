@props([
    "title" => null,
    "subtitle" => null,
    "icon" => null,
    "titleLvl" => 3,
])

<div {{ $attributes->class([
    "card",
]) }}>
    @if ($title)
    <div class="header">
        <div class="titles">
            @if ($icon)
            <x-shipyard.app.h :lvl="$titleLvl" role="card-icon" :icon="$icon" />
            @endif

            <div role="texts">
                <x-shipyard.app.h :lvl="$titleLvl" role="card-title">{{ $title }}</x-shipyard.app.h>
                @if ($subtitle)
                {!! $subtitle !!}
                @endif
            </div>
        </div>

        <div class="actions">
            @isset($actions)
            {{ $actions }}
            @endisset
        </div>
    </div>
    @endif

    <x-shipyard.app.loader horizontal />

    @isset ($slot)
    <div class="contents">
        {{ $slot }}
    </div>
    @endisset
</div>
