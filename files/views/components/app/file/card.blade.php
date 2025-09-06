@props([
    "file",
])

<div role="file-card">
    <strong role="file-name">{{ Str::afterLast($file, '/') }}</strong>
    @if (isPicture($file))
    <img src="{{ Storage::url($file) }}?{{ time() }}" alt="{{ Str::afterLast($file, '/') }}" class="thumbnail">
    @else
    <span>brak podglądu</span>
    @endif

    <div class="actions">
        @if (request()->has("select"))
        <x-shipyard.ui.button
            icon="check"
            pop="Wybierz"
            action="none"
            onclick="selectFile('{{ asset(Storage::url($file)) }}', '{{ request('select') }}')"
            class="primary"
        />
        @else
        <x-shipyard.ui.button
            icon="download"
            pop="Pobierz"
            :action="route('files.download', ['file' => $file])"
            target="_blank"
        />
        <x-shipyard.ui.button
            icon="link"
            pop="Link"
            action="none"
            onclick="copyToClipboard('{{ asset(Storage::url($file)) }}')"
        />
        <x-shipyard.ui.button
            icon="pencil"
            pop="Podmień"
            action="none"
            onclick="initFileReplace('{{ Str::afterLast($file, '/') }}')"
        />
        <x-shipyard.ui.button
            icon="delete"
            pop="Usuń"
            :action="route('files.delete', ['file' => $file])"
            class="danger"
        />
        @endif
    </div>
</div>
