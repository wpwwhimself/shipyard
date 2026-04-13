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
        :action="route(
            $action['route'],
            collect($action['params'] ?? [])
                ->mapWithKeys(fn ($prop, $key) => [$key => $data->{$prop}])
                ->toArray()
        )"
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

@if ($meta['description'])
<x-shipyard.app.card>
    <p>{{ $meta['description'] }}</p>
</x-shipyard.app.card>
@endif

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
    @class([
        "hidden" => !($sorts || $filters),
    ])
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
            onchange="getModelList();"
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
            onchange="getModelList();"
        />
        @endif
    </x-shipyard.app.form>
</x-shipyard.app.section>

@foreach ($extraSections as $esid => $esdata)
<x-shipyard.app.section
    :title="$esdata['title']"
    :icon="$esdata['icon']"
    :id="$esid"
>
    <x-dynamic-component
        :component="$esdata['component']"
        :data="new (model($scope))()"
    />
</x-shipyard.app.section>
@endforeach

<x-shipyard.app.card id="model-list">
</x-shipyard.app.card>

@endsection

@section("prepends")
<script>
function getModelList(page = null) {
    const filterForm = document.forms[0];
    filterForm.querySelector("input[name='page']").value = page;
    const filterFormData = new FormData(filterForm);

    window.scrollTo({top: 0, behavior: 'smooth'});

    fetchComponent(
        `#model-list > .loader`,
        filterForm.action,
        {
            method: filterForm.method,
            body: filterFormData,
        },
        [
            [`#model-list > .contents`, `html`],
        ],
        (res) => {
            window.history.pushState(null, null, res.url);
            reapplyPopper();
            reinitSelect();
        }
    );
}
</script>
@endsection

@section("appends")
<script>
// init list
getModelList({{ request('page') }});
</script>
@endsection
