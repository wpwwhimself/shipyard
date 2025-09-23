@props([
    "connections" => [],
    "model",
])

@foreach ($connections as $connection_name => $data)
<span>
    <span {{ Popper::pop($data['field_label'] ?? model($connection_name)::META['label']) }}>
        <x-shipyard.app.icon :name="model_icon($connection_name)" />
    </span>
    <span>{!! $data["mode"] === "many"
        ? $model->{$connection_name}->map(fn ($i) => $i->__toString())->join(", ")
        : $model->{$connection_name} !!}
    </span>
</span>
@endforeach