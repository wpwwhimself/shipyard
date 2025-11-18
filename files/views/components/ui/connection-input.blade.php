@props([
    "model",
    "connectionName",
])

@php
$rdata = $model::getConnections()[$connectionName];
$models = collect($rdata["model"]);
$pop_label = $rdata["field_label"]
    ?? $models->map(fn ($i) => $i::META["label"])->join("/");
$icon = $rdata["field_icon"]
    ?? ($models->count() > 1
        ? "link"
        : $models->first()::META["icon"]
    );
$options = $models->flatMap(fn ($m) => $m::all()->map(fn ($i) => [
        'label' => $i->option_label,
        'value' => $i->id,
        'group' => $i::META["label"],
        'type' => $i::class,
    ]))
    ->groupBy("group");
@endphp

@switch ($rdata['mode'])
    @case ("one")
    <x-shipyard.ui.input type="select"
        :name="$rdata['field_name'] ?? Str::snake($connectionName).'_id'"
        :label="$rdata['field_label'] ?? 'Wybierz'"
        :icon="$icon"
        :value="$models->count() > 1
            ? implode(':', [$model?->{Str::snake($connectionName).'_type'}, $model?->{$rdata['field_name'] ?? Str::snake($connectionName).'_id'}])
            : $model?->{$rdata['field_name'] ?? Str::snake($connectionName).'_id'}
        "
        :select-data="[
            'options' => $options,
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
