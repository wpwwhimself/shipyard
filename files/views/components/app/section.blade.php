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
            <h2><x-shipyard.app.icon :name="$icon" /></h2>
            <h2>{{ $title }}</h2>
            <span class="ghost">{!! $subtitle !!}</span>
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

    <div @class(['body', 'hidden' => !$extended])>
        {{ $slot }}
    </div>
</div>
