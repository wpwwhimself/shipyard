@props([
    "connections" => [],
    "model",
])

<div>
    @foreach ($connections as $connection_name => $data)
    <div>
        <span {{ Popper::pop($data['field_label'] ?? $data['model']::META['label']) }}>
            <x-shipyard.app.icon :name="$data['model']::META['icon']" />
        </span>
        <span>{!! $data["mode"] === "many"
            ? $model->{$connection_name}->map(fn ($i) => $i->__toString())->join(", ")
            : $model->{$connection_name} !!}
        </span>
    </div>
    @endforeach
</div>