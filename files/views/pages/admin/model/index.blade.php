@extends("layouts.shipyard.admin")
@section("title", "Zarządzanie modelami")
@section("subtitle", "Administracja")

@section("sidebar")

<div class="card stick-top">
    @foreach ($model_groups as $group)
    <x-shipyard.ui.button
        :icon="$group['icon']"
        :pop="$group['label']"
        action="none"
        onclick="jumpTo('#{{ $group['id'] }}')"
        class="tertiary"
    />
    @endforeach
</div>

@endsection

@section("content")

<div @class(["flex", "down", "stagger-contents" => setting("animations_mode") >= 1])>

@foreach ($model_groups as $group)
<x-shipyard.app.section
    :title="$group['label']"
    :icon="$group['icon']"
    id="{{ $group['id'] }}"
>
    <div @class(["grid", "but-mobile-down", "stagger-contents" => setting("animations_mode") >= 2]) style="--col-count: 3;">
        @foreach ($group["models"] as $model)
        <x-shipyard.ui.button
            :icon="$model['icon'] ?? null"
            :label="$model['label']"
            :action="route('admin.model.list', ['model' => $model['scope']])"
            :show-for="$model['role'] ?? null"
        />
        @endforeach
    </div>
</x-shipyard.app.section>
@endforeach

</div>

@endsection
