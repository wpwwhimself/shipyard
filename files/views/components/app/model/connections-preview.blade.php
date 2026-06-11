@props([
    "connections" => [],
    "model",
])

<div>
    @foreach ($connections as $connection_name => $data)
    @continue (!$model->{$connection_name} || $model->{$connection_name}->count() == 0)

    @php
    $models = collect($data["model"]);
    $pop_label = $data["field_label"]
        ?? $models->map(fn ($i) => $i::META["label"])->join("/");
    $icon = $data["field_icon"]
        ?? ($models->count() > 1
            ? "link"
            : $models->first()::META["icon"]
        );
    @endphp

    <x-shipyard.app.icon-label-value
        :icon="$icon"
        :label="$pop_label"
    >
        {!! Str::startsWith($data["mode"], "many")
            ? $model->{$connection_name}->map(fn ($i) => $i->__toString())->join(", ")
            : $model->{$connection_name} !!}
    </x-shipyard.app.icon-label-value>
    @endforeach
</div>
