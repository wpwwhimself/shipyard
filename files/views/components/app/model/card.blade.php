@props([
    "model",
])

<div role="model-card" {{ $attributes }}>
    <div role="top-part">
        <div role="model-intro">
            <x-shipyard.app.h :lvl="3"
                role="card-title"
                :icon="$model->icon ?? $model::META['icon']"
            >
                {{ $model->name }}
            </x-shipyard.app.h>

            @isset ($model->description)
            <span>{{ $model->description }}</span>
            @endisset

            @isset ($model->badges)
            <x-shipyard.app.model.badges :badges="$model->badges" />
            @endisset
        </div>
    </div>

    <div role="middle-part">
    </div>

    <div role="bottom-part">
        <div role="timestamps">
            {{-- todo timestamps --}}
        </div>

        @isset ($actions)
        {{ $actions }}
        @endisset
    </div>
</div>
