@props([
    "rank",
    "label" => null,
    "style" => "dots",
])

<span @class(["counter", $style])
    {{ Popper::pop(($label ? "$label: " : "") . $rank) }}
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
        <x-shipyard.app.icon :name="'tally-mark-'.($i >= 5 ? 5 : $i)" />
        @php $i -= 5; @endphp
        @endwhile
        @break

        @case ("military")
        @php
        $scale = 2.75;
        $space = 0.25 * $scale;
        $height = $scale * 8 / 3;

        $counts = [
            "bars" => 0,
            "bars2" => 0,
            "chevrons" => 0,
            "chevrons2" => 0,
            "stars" => 0,
            "stars2" => 0,
            "waves" => 0,
        ];
        $offset = 0;
        $i = $rank;

        while ($i > 0) {
            if ($i >= 100) {
                $counts["waves"]++;
                $i -= 100;
            } elseif ($i >= 20) {
                $counts["stars"]++;
                $i -= 20;
            } elseif ($i >= 5) {
                $counts["chevrons"]++;
                $i -= 5;
            } else {
                $counts["bars"]++;
                $i--;
            }
        }

        // combine pips to save space
        $elements_scaledown_threshold = 5;
        foreach([
            "stars",
            "bars",
            "chevrons",
        ] as $piptype) {
            $total_elements = array_sum($counts);
            if ($total_elements > $elements_scaledown_threshold) {
                if ($counts[$piptype] > 1) {
                    $counts[$piptype."2"] += floor($counts[$piptype] / 2);
                    $counts[$piptype] -= $counts[$piptype."2"] * 2;
                }
            }
        }

        // if there's still too many pips, scale them down to fit in the rectangle
        $total_elements = array_sum($counts);
        $scale = ($total_elements > $elements_scaledown_threshold)
            ? $scale * $elements_scaledown_threshold / $total_elements
            : $scale;
        @endphp

        <svg height="{{ 2.3 * $height }}" width="{{ $height }}">
            @if ($rank <= 0)
            <rect y="{{ $offset }}" width="1" role="null" width="{{ $height }}" />

            @else
            @foreach ([
                "waves",
                "bars2",
                "bars",
                "stars2",
                "stars",
                "chevrons2",
                "chevrons",
            ] as $piptype)
                @for ($i = 0; $i < $counts[$piptype]; $i++)
                @switch ($piptype)
                    @case ("bars")
                    <rect y="{{ $offset }}" height="{{ $scale * 2/3 }}" width="{{ $height }}" />
                    @php $offset += $scale * 2/3; @endphp
                    @break

                    @case ("bars2")
                    <rect y="{{ $offset }}" height="{{ $scale * 2/3 }}" width="{{ $height * 0.4 }}" />
                    <rect y="{{ $offset }}" x="{{ $height * 0.6 }}" height="{{ $scale * 2/3 }}" width="{{ $height * 0.4 }}" />
                    @php $offset += $scale * 2/3; @endphp
                    @break

                    @case ("chevrons")
                    <path d="M0 {{ $offset }}
                        l{{ $height / 2 }} {{ $scale * 0.8 }}
                        l{{ $height / 2 }} {{ -$scale * 0.8 }}
                        l0 {{ $scale * 0.8 }}
                        l{{ -$height / 2 }} {{ $scale * 0.8 }}
                        l{{ -$height / 2 }} {{ -$scale * 0.8 }}
                        l0 {{ -$scale * 0.8 }}
                    " />
                    @php $offset += $scale * 0.9; @endphp
                    @break

                    @case ("chevrons2")
                    <path d="M0 {{ $offset }}
                        l{{ $height * 0.4 }} {{ $scale * 0.6 }}
                        l0 {{ $scale * 0.8 }}
                        l{{ -$height * 0.4 }} {{ -$scale * 0.6 }}
                        l0 {{ -$scale * 0.8 }}
                        M{{ $height }} {{ $offset }}
                        l{{ -$height * 0.4 }} {{ $scale * 0.6 }}
                        l0 {{ $scale * 0.8 }}
                        l{{ $height * 0.4 }} {{ -$scale * 0.6 }}
                        l0 {{ -$scale * 0.8 }}
                    " />
                    @php $offset += $scale * 0.9; @endphp
                    @break

                    @case ("stars")
                    <path d="M{{ $height / 2 }} {{ $offset + $scale * 0.9 }}
                        l{{ -$scale * 0.3 }} {{ -$scale * 0.9 }}
                        l{{ $scale * 0.77 }} {{ $scale * 0.55 }}
                        l{{ -$scale * 0.94 }} 0
                        l{{ $scale * 0.77 }} {{ -$scale * 0.55 }}
                        l{{ -$scale * 0.3 }} {{ $scale * 0.9 }}
                    " />
                    @php $offset += $scale * 5/6; @endphp
                    @php if($i + 1 == $counts[$piptype]) $offset -= $scale * 1/2; @endphp
                    @break

                    @case ("stars2")
                    <path d="M{{ $height / 4 }} {{ $offset + $scale * 0.9 }}
                        l{{ -$scale * 0.3 }} {{ -$scale * 0.9 }}
                        l{{ $scale * 0.77 }} {{ $scale * 0.55 }}
                        l{{ -$scale * 0.94 }} 0
                        l{{ $scale * 0.77 }} {{ -$scale * 0.55 }}
                        l{{ -$scale * 0.3 }} {{ $scale * 0.9 }}
                    " />
                    <path d="M{{ $height * 3/4 }} {{ $offset + $scale * 0.9 }}
                        l{{ -$scale * 0.3 }} {{ -$scale * 0.9 }}
                        l{{ $scale * 0.77 }} {{ $scale * 0.55 }}
                        l{{ -$scale * 0.94 }} 0
                        l{{ $scale * 0.77 }} {{ -$scale * 0.55 }}
                        l{{ -$scale * 0.3 }} {{ $scale * 0.9 }}
                    " />
                    @php $offset += $scale * 5/6; @endphp
                    @php if($i + 1 == $counts[$piptype]) $offset -= $scale * 1/4; @endphp
                    @break

                    @case ("waves")
                    <path d="M{{ -$height / 3 }} {{ $offset }}
                        @for ($ii = 0; $ii < 5; $ii++)
                        l{{ $height / 2 }} {{ $scale }}
                        l{{ -$height / 4 }} 0
                        l{{ -$height / 2 }} {{ -$scale }}
                        l{{ $height / 4 }} 0
                        m{{ $height / 3 }} 0
                        @endfor
                    " />
                    @php $offset += $scale; @endphp
                    @break
                @endswitch
                @php $offset += $space; @endphp
                @endfor
            @endforeach
            @endif
        </svg>
        @break
    @endswitch
</span>
