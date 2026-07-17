@props([
    "data" => [], // ["icon", "label", "value"]
    "hideEmpty" => false,
])

<div>
    @foreach ($data as $d)
    @continue ($hideEmpty && empty($d["value"]))

    <x-shipyard::app.icon-label-value
        :icon="$d['icon'] ?? null"
        :label="$d['label'] ?? null"
    >
        {{ $d["value"] ?? "—" }}
    </x-shipyard::app.icon-label-value>
    @endforeach
</div>
