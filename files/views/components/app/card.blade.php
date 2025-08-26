@props([
    "title" => null,
    "subtitle" => null,
    "icon" => null,
])

<div class="card">
    @if ($title || $subtitle || $icon)
    <div role="top-bar">
        <div class="title-wrapper">
            @if ($title)
            <h2 role="title">
                @if ($icon) <i class="fa-solid fa-{{ $icon }}"></i> @endif
                {{ $title }}
            </h2>
            @endif
            @if ($subtitle) <h3 role="subtitle">{{ $subtitle }}</h3> @endif
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
