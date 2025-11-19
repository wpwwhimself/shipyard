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

    @if ($sorts || $filters)
    <x-shipyard.app.section
        title="Filtry i sortowanie"
        :subtitle="request('sort') || request('fltr')
            ? implode(', ', array_filter([
                request('fltr') ? 'Filtry: ' . count(request('fltr')) : null,
                request('sort') ? 'Sortowanie: ' . model($scope)::getSorts()[Str::after(request('sort'), '-')]['label'] : null,
            ]))
            : null"
        icon="filter"
        :extended="false"
    >
        <x-slot:actions>
            @if (request('sort') || request('fltr'))
            <x-shipyard.ui.button
                :action="route('admin.model.list', ['model' => $scope])"
                icon="undo-variant"
                pop="Wyczyść filtry"
            />
            @endif
        </x-slot:actions> 

        <x-shipyard.app.form method="post" :action="route('admin.model.list.filter', ['model' => $scope])">
            <input type="hidden" name="page" value="{{ request('page') }}">

            @foreach ($filters as $fname => $fdata)
            <x-shipyard.ui.input :type="$fdata['type']"
                :name="'fltr['.$fname.']'"
                :label="$fdata['label']"
                :icon="isset($fdata['icon'])
                    ? $fdata['icon']
                    : ($fdata['compare-using'] == 'field'
                        ? model_field_icon($scope, $fname)
                        : null
                    )"
                :value="request('fltr.' . $fname)"
                :select-data="$fdata['selectData'] ?? null"
                onchange="this.form.submit()"
            />
            @endforeach

            @if ($sorts)
            <x-shipyard.ui.input type="select"
                name="sort"
                label="Sortuj"
                icon="sort"
                :select-data="[
                    'options' => collect($sorts)
                        ->flatMap(fn ($sdata, $sname) => isset($sdata['direction'])
                            ? [
                                [
                                    'label' => $sdata['label'],
                                    'value' => $sname,
                                ],
                            ] 
                            : [
                                [
                                    'label' => $sdata['label'] . ' (rosnąco)',
                                    'value' => $sname,
                                ],
                                [
                                    'label' => $sdata['label'] . ' (malejąco)',
                                    'value' => '-' . $sname,
                                ],
                            ]),
                ]"
                :value="request('sort')"
                onchange="this.form.submit()"
            />
            @endif
        </x-shipyard.app.form>
    </x-shipyard.app.section>
    @endif

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
