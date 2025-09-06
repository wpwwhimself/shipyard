@extends("layouts.shipyard.admin")
@section("title", "Wyszukiwanie plików")
@section("subtitle", "Zarządzanie plikami")

@section("content")

<div class="card">
    <x-shipyard.app.form>
        <p>
            Ten panel pozwala na wyszukiwanie plików znajdujących się w repozytorium.
            Po wpisaniu hasła wyświetlone zostaną ścieżki do wszystkich plików o podobnej nazwie lub lokalizacji zawierającej wskazane hasło.
        </p>

        <x-shipyard.ui.input type="text"
            name="q"
            label="Fraza"
            icon="magnify"
            :value="request('q')"
            autofocus
        />

        <x-slot:actions>
            <x-shipyard.ui.button
                icon="magnify"
                label="Szukaj"
                action="submit"
                class="primary"
            />
            <x-shipyard.ui.button
                icon="arrow-left"
                label="Wróć"
                :action="route('files')"
            />
        </x-slot:actions>
    </x-shipyard.app.form>
</div>

@if (request("q"))
<x-shipyard.app.card
    title="Wyniki wyszukiwania"
    icon="magnify"
>
    @forelse ($files as $file)
    <a class="flex-right middle" href="{{ route('files', ['path' => Str::contains($file, '/') ? Str::beforeLast($file, '/') : null]) }}">
        <img class="inline" src="{{ asset(Storage::url($file)) }}" {{ Popper::pop("<img class='thumbnail' src='".asset(Storage::url($file))."' />") }} />
        {{ $file }}
    </a>
    @empty
    <p class="ghost">Brak wyników</p>
    @endforelse
</x-shipyard.app.card>
@endif

@endsection
