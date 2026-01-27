@props([
    "name",
    "value" => null,
])

@if ($value)
<input type="hidden" name="{{ $name }}" value="{{ $value }}">
@endif

<div class="abc-preview" for="{{ $name }}">
    <div class="flex right center wrap" role="options">
        @if ($value)
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

        @else
        <x-shipyard.ui.button
            icon="music-note-plus"
            pop="Transponuj w górę"
            action="none"
            class="tertiary"
            name="up"
            onclick="abcTransposeUp(document.querySelector(`[name='{{ $name }}']`)); abcPreview('{{ $name }}');"
        />
        <x-shipyard.ui.button
            icon="music-note-minus"
            pop="Transponuj w dół"
            action="none"
            class="tertiary"
            name="down"
            onclick="abcTransposeDown(document.querySelector(`[name='{{ $name }}']`)); abcPreview('{{ $name }}');"
        />
        @endif
    </div>

    <div id="abc-preview-sheet-{{ $name }}"></div>
    <script defer>abcPreview('{{ $name }}');</script>
</div>
