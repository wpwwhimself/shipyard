@props([
    "model",
    "connectionName",
    "value",
])

@php
$rdata = $model::getConnections()[$connectionName];
@endphp

@switch ($rdata['mode'])
    @case ("one")
    <x-shipyard.ui.input type="select"
        :name="$rdata['field_name'] ?? Str::snake($connectionName).'_id'"
        :label="$rdata['field_label'] ?? 'Wybierz'"
        :icon="$rdata['model']::META['icon']"
        :value="$model?->{$rdata['field_name'] ?? Str::snake($connectionName).'_id'}"
        :select-data="[
            'options' => $rdata['model']::all()->map(fn ($i) => [
                'label' => $i->option_label,
                'value' => $i->id,
            ]),
            'emptyOption' => true,
        ]"
    />
    @break

    @case ("many")
    <div class="grid" style="--col-count: 3;">
    @foreach ($rdata['model']::all() as $item)
    @if ($connectionName == "roles" && $item->name == "super" && !auth()->user()->hasRole("super")) @continue @endif
    <x-shipyard.ui.input type="checkbox"
        name="{{ $connectionName }}[]"
        :label="$item->name"
        :icon="$item->icon ?? null"
        :hint="$item->description"
        :value="$item->id ?? $item->name"
        :checked="$model?->{$connectionName}->contains($item)"
    />
    @endforeach
    </div>
    @break
@endswitch
