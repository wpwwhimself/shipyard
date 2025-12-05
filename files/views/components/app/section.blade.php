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
            <x-shipyard.ui.button
                icon="unfold-less-horizontal"
                pop="Zwiń"
                action="none"
                onclick="openSection(this, '{{ $key }}')"
                class="toggles tertiary {{ $extended ? '' : 'hidden' }}"
            />
            <x-shipyard.ui.button
                icon="unfold-more-horizontal"
                pop="Rozwiń"
                action="none"
                onclick="openSection(this, '{{ $key }}')"
                class="toggles tertiary {{ $extended ? 'hidden' : '' }}"
            />
            @endunless
        </div>
    </div>
    @endif

    @isset ($slot)
    <div @class(['contents', 'hidden' => !$extended])>
        {{ $slot }}
    </div>
    @endisset
</div>
