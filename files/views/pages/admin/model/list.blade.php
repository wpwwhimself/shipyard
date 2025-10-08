@extends("layouts.shipyard.admin")
@section("title", $meta["label"])
@section("subtitle", "Administracja")

@section("sidebar")

<div class="card stick-top">
    <x-shipyard.ui.button
        icon="plus"
        pop="Dodaj"
        class="primary"
        :action="route('admin.model.edit', ['model' => $scope])"
    />

    @if ($actions)
    <x-shipyard.app.sidebar-separator />

    @foreach ($actions as $action)
    <x-shipyard.ui.button
        :icon="$action['icon']"
        :pop="$action['label']"
        :action="route($action['route'], ['id' => $data->id])"
        class="{{ ($action['dangerous'] ?? false) ? 'danger' : '' }}"
        :show-for="$action['role'] ?? null"
    />
    @endforeach
    @endif

    <x-shipyard.app.sidebar-separator />

    @foreach (similar_models($scope) as $model)
    <x-shipyard.ui.button
        :icon="$model['icon'] ?? null"
        :pop="$model['label']"
        :action="route('admin.model.list', ['model' => $model['scope']])"
    />
    @endforeach
</div>

@endsection

@section("content")

<x-shipyard.app.card>
    <p>{{ $meta['description'] }}</p>

    @forelse ($data as $item)
    <x-shipyard.app.model.tile :model="$item" class="{{ $item->is_uneditable ? 'ghost' : null }}">
        <x-slot:actions>
            @unless ($item->is_uneditable)
            <x-shipyard.ui.button
                icon="pencil"
                label="Edytuj"
                :action="route('admin.model.edit', ['model' => $scope, 'id' => $item->getKey()])"
            />
            @endunless
        </x-slot:actions>
    </x-shipyard.app.model.tile>
    @empty
    <div role="empty">Brak danych do wyświetlenia</div>
    @endforelse

    {{ $data->links("components.shipyard.pagination.default") }}
</x-shipyard.app.card>

@endsection
