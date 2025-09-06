@props([
    "directories" => [],
])

<nav role="files-breadcrumbs">
    <span>Jesteś tutaj:</span>
    <strong role="current-directory">{{ request('path', 'Katalog główny') }}</strong>

    @if (!in_array(request("path"), ["public", null]))
    <x-shipyard.ui.button
        label=".."
        icon="arrow-left"
        :action="route('files', [
            'path' => Str::contains(request('path'), '/') ? Str::beforeLast(request('path'), '/') : null,
            'select' => request('select'),
        ])"
    />
    @endif

    @foreach ($directories as $dir)
    <x-shipyard.ui.button
        :label="Str::afterLast($dir, '/')"
        icon="folder"
        :action="route('files', ['path' => $dir, 'select' => request('select')])"
    />
    @endforeach
</nav>
