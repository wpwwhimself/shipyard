@props([
    "total" => 9,
    "current" => 1,
    "color" => "var(--primary)",
    "clickFunctions" => [], // array of things for every phase: [function, pop label]
])

<div class="phase-bar" style="
    --highlight-color: {{ $color }};
    --total: {{ $total }};
">
    @isset ($slot)
    <div role="label">
        {{ $slot }}
    </div>
    @endisset

    <div role="bars">
        @for ($i = 0; $i < $total; $i++)
        <div @class([
            "highlighted" => $i < $current,
            "interactive" => isset($clickFunctions[$i]) && $i != $current - 1,
        ])
            @if (isset($clickFunctions[$i]) && $i != $current - 1)
                onclick="{{ $clickFunctions[$i][0] }}"
                {{ isset($clickFunctions[$i][1]) ? Popper::pop($clickFunctions[$i][1]) : "" }}
            @endif
        >
        </div>
        @endfor
    </div>
</div>
