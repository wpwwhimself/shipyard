@props([
    "connections" => [],
    "model",
])

<div>
    @foreach ($connections as $connection_name => $data)
    <div>
        <span {{ Popper::pop($data['field_label'] ?? model($connection_name)::META['label']) }}>
            <x-shipyard.app.icon :name="model_icon($connection_name)" />
        </span>
        <span>{!! $data["mode"] === "many"
            ? $model->{$connection_name}->map(fn ($i) => $i->__toString())->join(", ")
            : $model->{$connection_name} !!}
        </span>
    </div>
    @endforeach
</div>