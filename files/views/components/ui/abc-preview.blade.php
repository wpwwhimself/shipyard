@props([
    "name",
    "value" => null,
    "live" => false,
])

@if ($value)
<input type="hidden" name="{{ $name }}" value="{{ $value }}">
@endif

@if ($live || $value)
<div class="abc-preview" for="{{ $name }}">
    @unless ($live)
    <div class="flex right center wrap" role="options">
        <x-shipyard.ui.button
            icon="music-clef-treble"
            pop="Transpozycja"
            label="0"
            class="transposer-main-btn toggle"
            action="none"
            onclick="abcTransposer('{{ $name }}');"
        />
        @foreach ([
            ["music-accidental-sharp", "W górę", 1],
            ["music-accidental-flat", "W dół", -1],
            ["music-accidental-natural", "Resetuj", 0],
        ] as [$icon, $label, $mode])
        <x-shipyard.ui.button
            :icon="$icon"
            :pop="$label"
            class="transposer-btn hidden tertiary"
            action="none"
            onclick="abcTransposer('{{ $name }}', {{ $mode }});"
        />
        @endforeach
    </div>
    @endunless

    <div id="abc-preview-sheet-{{ $name }}"></div>
    <script defer>abcPreview('{{ $name }}');</script>
</div>
@endif
