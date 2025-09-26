@extends("layouts.shipyard.admin")
@section("title", "Zarządzanie modelami")
@section("subtitle", "Administracja")

@section("sidebar")

<div class="card stick-top">
    @foreach ($model_groups as $group)
    <x-shipyard.ui.button
        :icon="$group['icon']"
        :pop="$group['label']"
        pop-direction="right"
        action="none"
        onclick="jumpTo('#{{ $group['id'] }}')"
        class="tertiary"
    />
    @endforeach
</div>

@endsection

@section("content")

@foreach ($model_groups as $group)
<x-shipyard.app.card
    :title="$group['label']"
    :icon="$group['icon']"
    id="{{ $group['id'] }}"
>
    <div class="grid" style="--col-count: 3;">
        @foreach ($group["models"] as $model)
        <x-shipyard.ui.button
            :icon="$model['icon'] ?? null"
            :label="$model['label']"
            :action="route('admin.model.list', ['model' => $model['scope']])"
            :show-for="$model['role'] ?? null"
        />
        @endforeach
    </div>
</x-shipyard.app.card>
@endforeach

@endsection
