@extends("layouts.shipyard.admin")
@section("title", "Repozytorium plików")
@section("subtitle", "Administracja")

@section("sidebar")

<div class="card stick-top">
    @foreach ($sections as $id => $item)
    <x-shipyard.ui.button
        :icon="$item['icon'] ?? null"
        :pop="$item['label']"
        pop-direction="right"
        action="#{{ $id }}"
        class="tertiary"
    />
    @endforeach
</div>

@endsection

@section("content")

<p>
    Tutaj możesz umieszczać pliki – np. grafiki – które mają pojawić się na podstronach.
    Po wgraniu ich na serwer możesz je umieścić w treściach strony, korzystając z wygenerowanych linków.
</p>

@php $card_id = "files-list"; @endphp
<x-shipyard.app.card
    :title="$sections[$card_id]['label']"
    :icon="$sections[$card_id]['icon']"
    :id="$card_id"
>
    <x-slot:actions>
        <x-shipyard.ui.button
            label="Znajdź pliki"
            icon="magnify"
            :action="route('files.search')"
        />
    </x-slot:actions>

    <x-shipyard.app.file.breadcrumbs :directories="$directories" />

    <div role="files-list">
        @forelse ($files as $file)
        <x-shipyard.app.file.card :file="$file" />
        @empty
        <p>Brak plików</p>
        @endforelse
    </div>
</x-shipyard.app.card>

<div class="grid" style="--col-count: 2;">
    @php $card_id = "files-upload"; @endphp
    <x-shipyard.app.card
        :title="$sections[$card_id]['label']"
        :icon="$sections[$card_id]['icon']"
        :id="$card_id"
    >
        <x-shipyard.app.form
            :action="route('files.upload')"
            method="post"
            enctype="multipart/form-data"
        >
            <input type="hidden" name="path" value="{{ request("path") }}">
            <input type="hidden" name="force_file_name">
            <input type="file" name="files[]" id="files" multiple>

            <span>Pliki zostaną zapisane w obecnie wybranym katalogu.</span>

            <x-slot:actions>
                <x-shipyard.ui.button
                    label="Wgraj"
                    icon="upload"
                    action="submit"
                    class="primary"
                />
            </x-slot:actions>
        </x-shipyard.app.form>
    </x-shipyard.app.card>

    @php $card_id = "folder-mgmt"; @endphp
    <x-shipyard.app.card
        :title="$sections[$card_id]['label']"
        :icon="$sections[$card_id]['icon']"
        :id="$card_id"
    >
        <x-shipyard.app.form
            :action="route('files.folder.create')"
            method="post"
        >
            <input type="hidden" name="path" value="{{ request("path") }}">

            <p>Utwórz nowy podfolder, podając jego nazwę.</p>
            <x-shipyard.ui.input
                name="name"
                label="Nazwa podfolderu"
                icon="folder-text"
            />
            <p>
                Utworzony zostanie nowy folder w katalogu <strong>{{ request("path", "głównym") }}</strong>
            </p>

            <x-slot:actions>
                <x-shipyard.ui.button
                    icon="folder-plus"
                    label="Utwórz"
                    action="submit"
                    class="primary"
                />
                @if (request('path'))
                <x-shipyard.ui.button
                    icon="folder-remove"
                    label="Usuń obecny folder i jego zawartość"
                    :action="route('files.folder.delete', ['path' => request('path')])"
                    class="danger phantom"
                />
                @endif
            </x-slot:actions>
        </x-shipyard.app.form>
    </x-shipyard.app.card>
</div>

@endsection
