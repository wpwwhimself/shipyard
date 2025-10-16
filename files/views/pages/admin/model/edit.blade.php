@extends("layouts.shipyard.admin")
@section("title", ($data?->name ?: "Nowy wpis"))
@section("subtitle", "Administracja | ".$meta["label"])

@section("sidebar")

<div class="card stick-top">
    @foreach ($sections as $section)
    <x-shipyard.ui.button
        :icon="$section['icon'] ?? null"
        :pop="$section['title']"
        class="tertiary"
        action="none"
        onclick="jumpTo('#{{ $section['id'] }}')"
    />
    @endforeach

    @if ($data && count($actions))
    <x-shipyard.app.sidebar-separator />

    @foreach ($actions as $action)
    <x-shipyard.ui.button
        :icon="$action['icon']"
        :pop="$action['label']"
        :action="route($action['route'], ['id' => $data->id])"
        class="{{ ($action['dangerous'] ?? false) ? 'danger' : '' }}"
        :show-for="isset($action['role']) ? $action['role'] : null"
    />
    @endforeach
    @endif
</div>

@endsection

@section("content")

<x-shipyard.app.form :action="route('admin.model.edit.process', ['model' => $scope])" method="POST">
    <input type="hidden" name="id" value="{{ $data?->getKey() ?? "" }}">

    <x-shipyard.app.card
        title="Dane podstawowe"
        :icon="model_icon($scope)"
        id="basic"
    >
        <x-slot:actions>
            <x-shipyard.app.model.timestamps :model="$data" />
        </x-slot:actions>

        @foreach ($fields as $name => $fdata)
        @if (isset($fdata["role"]) && !auth()->user()->hasRole($fdata["role"])) @continue @endif
        <x-shipyard.ui.field-input :model="$data" :field-name="$name" />
        @endforeach
    </x-shipyard.app.card>

    <div class="grid but-mobile down" style="--col-count: 2;">
        @foreach ($connections as $relation => $rdata)
        @if (isset($rdata["role"]) && !auth()->user()->hasRole($rdata["role"])) @continue @endif

        <x-shipyard.app.card
            :title="$rdata['model']::META['label']"
            :icon="$rdata['model']::META['icon']"
            id="connections_{{ $relation }}"
        >
            <input type="hidden" name="_connections[]" value="{{ $relation }}">
            <x-shipyard.ui.connection-input :model="$data" :connection-name="$relation" />
        </x-shipyard.app.card>
        @endforeach
    </div>

    <x-slot:actions>
        <div class="card">
            <x-shipyard.ui.button
                icon="content-save"
                label="Zapisz zmiany"
                class="primary"
                action="submit"
                name="method"
                value="save"
            />
            @if ($data)
            <x-shipyard.ui.button
                icon="delete"
                label="Usuń"
                class="danger"
                action="submit"
                name="method"
                value="delete"
            />
            @endif
            <x-shipyard.ui.button
                icon="arrow-left"
                label="Wróć"
                :action="Auth::user()->hasRole('technical')
                    ? route('admin.model.list', ['model' => $scope])
                    : route('profile')"
            />
        </div>
    </x-slot:actions>
</x-shipyard.app.form>

@endsection
