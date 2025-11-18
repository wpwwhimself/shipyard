@props([
    "connections" => [],
    "model",
])

<div>
    @foreach ($connections as $connection_name => $data)
    <div>
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

        <span {{ Popper::pop($pop_label) }}>
            <x-shipyard.app.icon :name="$icon" />
        </span>
        <span>{!! $data["mode"] === "many"
            ? $model->{$connection_name}->map(fn ($i) => $i->__toString())->join(", ")
            : $model->{$connection_name} !!}
        </span>
    </div>
    @endforeach
</div>