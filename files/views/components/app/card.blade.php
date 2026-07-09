@props([
    "title" => null,
    "subtitle" => null,
    "icon" => null,
    "titleLvl" => 3,
    "innerClass" => null,
    "innerStyle" => null,
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
                <span role="card-subtitle">{!! $subtitle !!}</span>
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
    <div @class(["contents", $innerClass]) @style([$innerStyle])>
        {{ $slot }}
    </div>
    @endisset
</div>
