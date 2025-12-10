@props([
    "title" => null,
    "subtitle" => null,
    "icon" => null,
    "extended" => "perma",
    "key" => null,
])

@php
$key ??= Str::uuid();
@endphp

<div {{ $attributes->class([
    "section",
]) }} data-ebid="{{ $key }}">
    @if ($title)
    <div class="header">
        <div class="titles">
            @if ($icon)
            <x-shipyard.app.h lvl="2" role="section-icon" :icon="$icon" />
            @endif

            <div role="texts">
                <x-shipyard.app.h lvl="2" role="section-title">{{ $title }}</x-shipyard.app.h>
                @if ($subtitle)
                {!! $subtitle !!}
                @endif
            </div>
        </div>

        <div class="actions">
            @isset ($actions)
            {{ $actions }}
            @endisset

            @unless($extended === "perma")
            <div role="section-extender">
                <span
                    onclick="openSection(this, '{{ $key }}')"
                    @class([
                        "toggles",
                        "ghost",
                        "interactive",
                        "hidden" => $extended,
                    ])
                    @popper(Rozwiń)
                >
                    <x-shipyard.app.icon name="unfold-more-horizontal" />
                </span>
                <span
                    onclick="openSection(this, '{{ $key }}')"
                    @class([
                        "toggles",
                        "ghost",
                        "interactive",
                        "hidden" => !$extended,
                    ])
                    @popper(Zwiń)
                >
                    <x-shipyard.app.icon name="unfold-less-horizontal" />
                </span>
            </div>
            @endunless
        </div>
    </div>
    @endif

    @isset ($slot)
    <div @class(['contents', 'hidden' => !$extended])>
        {{ $slot }}
    </div>
    @endisset

    @unless($extended === "perma")
    <div role="section-extender">
        <span
            onclick="openSection(this, '{{ $key }}')"
            @class([
                "toggles",
                "ghost",
                "interactive",
                "hidden" => $extended,
            ])
        >
            <x-shipyard.app.icon name="chevron-down" />
            Rozwiń
        </span>
        <span
            onclick="openSection(this, '{{ $key }}')"
            @class([
                "toggles",
                "ghost",
                "interactive",
                "hidden" => !$extended,
            ])
        >
            <x-shipyard.app.icon name="chevron-up" />
            Zwiń
        </span>
    </div>
    @endunless
</div>
