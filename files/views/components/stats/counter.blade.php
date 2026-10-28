@props([
    "rank",
    "label" => null,
    "style" => "dots",
])

<span @class(["counter", $style])
    {{ Popper::pop(implode("", [
        ($style == "military" ? view("shipyard::components.stats.counter.military", ["rank" => $rank, "large" => true])->render() : ""),
        ($label ? "$label: " : ""),
        $rank
    ])) }}
>
    @switch ($style)
        @case ("dots")
        @php
        $dots = [
            0 => "⠀",
            1 => "⠄",
            2 => "⠆",
            3 => "⠦",
            4 => "⠶",
            5 => "⠾",
            6 => "⠿",
        ];

        $output = "";
        $i = $rank;

        while ($i > 0) {
            $output .= $dots[($i >= 6) ? 6 : $i];
            $i -= 6;
        }
        @endphp

        <span>{{ $output }}</span>
        @break

        @case ("lines")
        @php
        $i = $rank;
        @endphp

        @while ($i > 0)
        <x-shipyard::app.icon :name="'tally-mark-'.($i >= 5 ? 5 : $i)" />
        @php $i -= 5; @endphp
        @endwhile
        @break

        @case ("military")
        <x-shipyard::stats.counter.military :rank="$rank" />
        @break
    @endswitch
</span>
