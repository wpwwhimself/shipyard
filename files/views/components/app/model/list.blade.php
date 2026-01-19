@props([
    "data" => [],
    "scope" => null,
])

@forelse ($data as $item)
<x-shipyard.app.model.tile :model="$item" @class(["ghost" => $item->is_uneditable ?? false])>
    <x-slot:actions>
        @unless ($item->is_uneditable)
            @if ($item->model_edit_button)
            {!! $item->model_edit_button !!}
            @else
            <x-shipyard.ui.button
                icon="pencil"
                label="Edytuj"
                :action="route('admin.model.edit', ['model' => $scope, 'id' => $item->getKey()])"
            />
            @endif
        @endunless
    </x-slot:actions>
</x-shipyard.app.model.tile>
@empty
<div role="empty">Brak danych do wyświetlenia</div>
@endforelse

{{ $data->links("components.shipyard.pagination.default", ["pageSwitcher" => "getModelList"]) }}
