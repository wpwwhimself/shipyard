@props([
    "badge",
    "large" => false,
])

@if ($large) <div role='model-badges' class='large'> @endif
<div class='medal'
    style='--medal-colors: {{ collect($badge['medal'])
        ->map(fn ($color, $offset) => [$color, $offset])
        ->sliding(2)
        ->map(fn ($cdata) => $cdata->values())
        ->map(fn ($cdata) => implode(', ', [
            implode(' ', [$cdata[0][0], $cdata[0][1].'%']),
            implode(' ', [$cdata[1][0], $cdata[0][1].'%'])
        ]))
        ->join(', ')
    }};'
>
    <div class='medal-texture'></div>
</div>
@if ($large) </div><br> @endif
