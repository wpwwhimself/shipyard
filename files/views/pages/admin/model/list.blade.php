@extends("layouts.shipyard.admin")
@section("title", $meta["label"])
@section("subtitle", "Administracja")

@section("content")

<x-shipyard.app.card>
    <x-shipyard.app.model.intro :meta="$meta" />

    @forelse ($data as $item)
    <x-tile :action="route('admin-edit-model', ['model' => $scope, 'id' => $item->id])"
        class="flex right spread middle" no-border
    >
        <x-h lvl="2">{!! $item !!}</x-h>

        <div class="flex right middle">
            @foreach ($item->badges ?? [] as $badge)
            @unless ($badge['show']) @continue @endunless
            <small>
                <x-icon :name="$badge['icon']" hint="{{ $badge['label'] }}" />
            </small>
            @endforeach

            @isset ($item->visible)
            <small>
                <x-icon name="eye" hint="Widoczność" />
                {{ App\Http\Controllers\AdminController::VISIBILITIES[$item->visible] }}
            </small>
            @endisset
        </div>
    </x-tile>
    @empty
    <div class="ghost">Brak danych do wyświetlenia</div>
    @endforelse

    {{ $data->links() }}

    <x-slot:actions>
        <x-button :action="route('admin-edit-model', ['model' => $scope])" icon="plus">Dodaj</x-button>
        @foreach ($actions as $action)
        <x-button :action="route($action['route'])" :icon="$action['icon']" class="phantom {{ ($action['dangerous'] ?? false) ? 'danger' : '' }}">{{ $action['label'] }}</x-button>
        @endforeach
        <x-button :action="route('entmgr-list', ['model' => $scope])" icon="eye" class="phantom">Przegląd danych</x-button>
        <x-button :action="route('profile')" icon="arrow-left" class="phantom">Wróć</x-button>
    </x-slot:actions>
</x-shipyard.app.card>

@endsection
