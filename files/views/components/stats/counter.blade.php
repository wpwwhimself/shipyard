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
        $scale = 8;
        $space = 0.25 * $scale;
        $height = 2 * $scale;

        $counts = [
            "bars" => 0,
            "chevrons" => 0,
            "stars" => 0,
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
        @endphp

        <svg height="{{ $height }}">
            @if ($rank <= 0)
            <rect x="{{ $offset }}" width="1" role="null" height="{{ $height }}" />

            @else
            @foreach ([
                "waves",
                "bars",
                "stars",
                "chevrons",
            ] as $piptype)
                @for ($i = 0; $i < $counts[$piptype]; $i++)
                @switch ($piptype)
                    @case ("bars")
                    <rect x="{{ $offset }}" width="{{ $scale * 2/3 }}" height="{{ $height }}" />
                    @php $offset += $scale * 2/3; @endphp
                    @break

                    @case ("chevrons")
                    <path d="M{{ $offset }} 0
                        l{{ $scale }} {{ $height / 2 }}
                        l{{ -$scale }} {{ $height / 2 }}
                        l{{ $scale }} 0
                        l{{ $scale }} {{ -$height / 2 }}
                        l{{ -$scale }} {{ -$height / 2 }}
                        l{{ -$scale }} 0
                    " />
                    @php $offset += $scale; @endphp
                    @break

                    @case ("stars")
                    <path d="M{{ $offset + $scale }} {{ $height / 2 }}
                        l{{ -$scale * 0.9 }} {{ -$scale * 0.3 }}
                        l{{ $scale * 0.55 }} {{ $scale * 0.77 }}
                        l0 {{ -$scale * 0.94 }}
                        l{{ -$scale * 0.55 }} {{ $scale * 0.77 }}
                        l{{ $scale * 0.9 }} {{ -$scale * 0.3 }}
                    " />
                    @php $offset += $scale * 5/6; @endphp
                    @php if($i + 1 == $counts[$piptype]) $offset -= $scale * 1/2; @endphp
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
