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

        @case ("military")
        @php
        $scale = 6;
        $space = 0.25 * $scale;
        $height = 16;

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

        <svg width="{{ 2.3 * $height }}" height="{{ $height }}">
            @if ($rank <= 0)
            <rect x="{{ $offset }}" width="1" role="null" height="{{ $height }}" />

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
                    <rect x="{{ $offset }}" width="{{ $scale * 2/3 }}" height="{{ $height }}" />
                    @php $offset += $scale * 2/3; @endphp
                    @break

                    @case ("bars2")
                    <rect x="{{ $offset }}" width="{{ $scale * 2/3 }}" height="{{ $height * 0.4 }}" />
                    <rect x="{{ $offset }}" y="{{ $height * 0.6 }}" width="{{ $scale * 2/3 }}" height="{{ $height * 0.4 }}" />
                    @php $offset += $scale * 2/3; @endphp
                    @break

                    @case ("chevrons")
                    <path d="M{{ $offset }} 0
                        l{{ $scale * 0.8 }} {{ $height / 2 }}
                        l{{ -$scale * 0.8 }} {{ $height / 2 }}
                        l{{ $scale * 0.8 }} 0
                        l{{ $scale * 0.8 }} {{ -$height / 2 }}
                        l{{ -$scale * 0.8 }} {{ -$height / 2 }}
                        l{{ -$scale * 0.8 }} 0
                    " />
                    @php $offset += $scale * 0.9; @endphp
                    @break

                    @case ("chevrons2")
                    <path d="M{{ $offset }} 0
                        l{{ $scale * 0.6 }} {{ $height * 0.4 }}
                        l{{ $scale * 0.8 }} 0
                        l{{ -$scale * 0.6 }} {{ -$height * 0.4 }}
                        l{{ -$scale * 0.8 }} 0
                        M{{ $offset }} {{ $height }}
                        l{{ $scale * 0.6 }} {{ -$height * 0.4 }}
                        l{{ $scale * 0.8 }} 0
                        l{{ -$scale * 0.6 }} {{ $height * 0.4 }}
                        l{{ -$scale * 0.8 }} 0
                    " />
                    @php $offset += $scale * 0.9; @endphp
                    @break

                    @case ("stars")
                    <path d="M{{ $offset + $scale * 0.9 }} {{ $height / 2 }}
                        l{{ -$scale * 0.9 }} {{ -$scale * 0.3 }}
                        l{{ $scale * 0.55 }} {{ $scale * 0.77 }}
                        l0 {{ -$scale * 0.94 }}
                        l{{ -$scale * 0.55 }} {{ $scale * 0.77 }}
                        l{{ $scale * 0.9 }} {{ -$scale * 0.3 }}
                    " />
                    @php $offset += $scale * 5/6; @endphp
                    @php if($i + 1 == $counts[$piptype]) $offset -= $scale * 1/2; @endphp
                    @break

                    @case ("stars2")
                    <path d="M{{ $offset + $scale * 0.9 }} {{ $height / 4 }}
                        l{{ -$scale * 0.9 }} {{ -$scale * 0.3 }}
                        l{{ $scale * 0.55 }} {{ $scale * 0.77 }}
                        l0 {{ -$scale * 0.94 }}
                        l{{ -$scale * 0.55 }} {{ $scale * 0.77 }}
                        l{{ $scale * 0.9 }} {{ -$scale * 0.3 }}
                    " />
                    <path d="M{{ $offset + $scale * 0.9 }} {{ $height * 3/4 }}
                        l{{ -$scale * 0.9 }} {{ -$scale * 0.3 }}
                        l{{ $scale * 0.55 }} {{ $scale * 0.77 }}
                        l0 {{ -$scale * 0.94 }}
                        l{{ -$scale * 0.55 }} {{ $scale * 0.77 }}
                        l{{ $scale * 0.9 }} {{ -$scale * 0.3 }}
                    " />
                    @php $offset += $scale * 5/6; @endphp
                    @php if($i + 1 == $counts[$piptype]) $offset -= $scale * 1/4; @endphp
                    @break

                    @case ("waves")
                    <path d="M{{ $offset }} {{ -$height / 3 }}
                        @for ($ii = 0; $ii < 5; $ii++)
                        l{{ $scale }} {{ $height / 2 }}
                        l0 {{ -$height / 4 }}
                        l{{ -$scale }} {{ -$height / 2 }}
                        l0 {{ $height / 4 }}
                        m0 {{ $height / 3 }}
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
