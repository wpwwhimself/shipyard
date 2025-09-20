@props([
    "title" => null,
    "subtitle" => null,
    "icon" => null,
])

<div class="chart">
    @if ($title)
    <div class="title">
        <div role="title-container">
            @if ($icon)
            <x-shipyard.app.icon :name="$icon" />
            @endif
            <div>
                <h2 role="chart-title">{{ $title }}</h2>
                @if ($subtitle)
                <h3 role="chart-subtitle">{{ $subtitle }}</h3>
                @endif
            </div>

            @isset($actions)
            <div class="actions">
                {{ $actions }}
            </div>
            @endisset
        </div>
    </div>
    @endif

    <div {{ $attributes->class("data") }}>
        {{ $slot }}
    </div>
</div>
