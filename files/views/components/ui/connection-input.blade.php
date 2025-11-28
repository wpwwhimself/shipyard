@props([
    "model",
    "connectionName",
    "dummy" => false,
])

@php
$rdata = $model::getConnections()[$connectionName];
$models = collect($rdata["model"]);
$field_label = $rdata["field_label"]
    ?? $models->map(fn ($i) => $i::META["label"])->join("/");
$icon = $rdata["field_icon"]
    ?? ($models->count() > 1
        ? "link"
        : $models->first()::META["icon"]
    );
$options = $models->flatMap(fn ($m) => $m::forConnection()->get()->map(fn ($i) => [
    'label' => $i->option_label,
    'value' => $i->getKey(),
    'group' => $i::META["label"],
    'type' => $i::class,
]));
if ($models->count() > 1) {
    $options = $options->groupBy("group");
}
// dd($model?->{$connectionName}->map(fn ($i) => $i->getKey())->toArray());
@endphp

@switch ($rdata['mode'])
    @case ("one")
    <x-shipyard.ui.input type="select"
        :name="$rdata['field_name'] ?? Str::snake($connectionName).'_id'"
        :label="$field_label"
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
    <x-shipyard.ui.input :type="$dummy ||($rdata['readonly'] ?? false) ? 'dummy-text' : 'select'"
        :name="($rdata['field_name'] ?? Str::snake($connectionName)).'[]'"
        :label="$field_label"
        :icon="$icon"
        :value="$dummy || ($rdata['readonly'] ?? false)
            ? $model?->{$connectionName}->map(fn ($i) => $i->option_label)->join(', ')
            : $model?->{$connectionName}->map(fn ($i) => $i->getKey())->toArray()
        "
        :select-data="[
            'options' => $options,
        ]"
        multiple
    />
    @break
@endswitch
