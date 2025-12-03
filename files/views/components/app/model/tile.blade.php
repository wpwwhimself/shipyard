@props([
    "model",
])

<div role="model-card" {{ $attributes }}>
    <div role="top-part">
        {!! $model->display_pre_title !!}
        <div role="model-intro">
            {!! $model->display_title !!}
            {!! $model->display_subtitle !!}
        </div>
    </div>

    <div role="middle-part">
        {!! $model->display_middle_part !!}
    </div>

    <div role="bottom-part">
        <div role="timestamps">
            <x-shipyard.app.model.timestamps :model="$model" />
        </div>

        @isset ($actions)
        {{ $actions }}
        @endisset
    </div>
</div>
