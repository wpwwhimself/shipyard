@extends("layouts.shipyard.admin")
@section("title", ($data?->name ?: "Nowy wpis"))
@section("subtitle", "Administracja | ".$meta["label"])

@section("sidebar")

<div class="card stick-top">
    @foreach ($sections as $section)
    <x-shipyard.ui.button
        :icon="$section['icon'] ?? null"
        :pop="$section['title']"
        pop-direction="right"
        class="tertiary"
        action="#{{ $section['id'] }}"
    />
    @endforeach

    @if ($data && count($actions))
    <x-shipyard.app.sidebar-separator />

    @foreach ($actions as $action)
    @if (isset($action["role"]) && !auth()->user()->hasRole($action["role"])) @continue @endif
    <x-shipyard.ui.button
        :icon="$action['icon']"
        :pop="$action['label']"
        pop-direction="right"
        :action="route($action['route'], ['id' => $data->id])"
        class="{{ ($action['dangerous'] ?? false) ? 'danger' : '' }}"
    />
    @endforeach
    @endif
</div>

@endsection

@section("content")

<x-shipyard.app.form :action="route('admin.model.edit.process', ['model' => $scope])" method="POST">
    <input type="hidden" name="id" value="{{ $data?->getKey() ?? "" }}">

    <x-shipyard.app.card
        title="Dane podstawowe"
        :icon="model_icon($scope)"
        id="basic"
    >
        <x-slot:actions>
            @if ($data && isset($data->created_by))
            <div class="flex right ghost">
                {{-- todo przerobić to i podobne na komponenty --}}
                <span>
                    <span @popper(Twórca)>@svg("mdi-account-plus")</span>
                    {{ $data->creator->name }},
                    <span {{ Popper::pop($data->created_at ?? "") }}>{{ $data->created_at?->diffForHumans() }}</span>
                </span>

                @if ($data->created_at != $data->updated_at)
                <span>
                    <span @popper(Ostatnia edycja)>@svg("mdi-account-edit")</span>
                    {{ $data->editor->name }},
                    <span {{ Popper::pop($data->updated_at ?? "") }}>{{ $data->updated_at?->diffForHumans() }}</span>
                </span>
                @endif
            </div>
            @endif
        </x-slot:actions>


        @foreach ($fields as $name => $fdata)
        @if (isset($fdata["role"]) && !auth()->user()->hasRole($fdata["role"])) @continue @endif
        <x-shipyard.ui.input :type="$fdata['type']"
            :name="$name"
            :label="$fdata['label']"
            :icon="$fdata['icon']"
            :value="$fdata['type'] == 'checkbox' ? 1 : $data?->{$name}"
            :checked="$fdata['type'] == 'checkbox' && $data?->{$name}"
            :select-data="$fdata['select-data'] ?? null"
            :required="$fdata['required'] ?? false"
            :placeholder="$fdata['placeholder'] ?? null"
            :hint="$fdata['hint'] ?? null"
            :column-types="$fdata['column-types'] ?? null"
            :autofill-from="$fdata['autofill-from'] ?? null"
            :character-limit="$fdata['character-limit'] ?? null"
        />
        @endforeach
    </x-shipyard.app.card>

    @foreach ($connections as $relation => $rdata)
    @if (isset($rdata["role"]) && !auth()->user()->hasRole($rdata["role"])) @continue @endif

    <x-shipyard.app.card
        :title="$rdata['model']::META['label']"
        :icon="$rdata['model']::META['icon']"
        id="connections_{{ $relation }}"
    >
        <input type="hidden" name="_connections[]" value="{{ $relation }}">
        @switch ($rdata['mode'])
            @case ("one")
            <x-shipyard.ui.input type="select"
                name="{{ Str::snake($relation) }}_id"
                label="Wybierz"
                :icon="$rdata['model']::META['icon']"
                :value="$data?->{Str::studly($relation)} ? $data?->{Str::studly($relation)}->id : null"
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
            @if ($relation == "roles" && $item->name == "super" && !auth()->user()->hasRole("super")) @continue @endif
            <x-shipyard.ui.input type="checkbox"
                name="{{ $relation }}[]"
                :label="$item->name"
                :icon="$item->icon ?? null"
                :hint="$item->description"
                :value="$item->id ?? $item->name"
                :checked="$data?->{$relation}->contains($item)"
            />
            @endforeach
            </div>
            @break
        @endswitch
    </x-shipyard.app.card>
    @endforeach

    <x-slot:actions>
        <div class="card">
            <x-shipyard.ui.button
                icon="content-save"
                label="Zapisz zmiany"
                class="primary"
                action="submit"
                name="method"
                value="save"
            />
            @if ($data)
            <x-shipyard.ui.button
                icon="delete"
                label="Usuń"
                class="danger"
                action="submit"
                name="method"
                value="delete"
            />
            @endif
            <x-shipyard.ui.button
                icon="arrow-left"
                label="Wróć"
                :action="Auth::user()->hasRole('technical')
                    ? route('admin.model.list', ['model' => $scope])
                    : route('profile')"
            />
        </div>
    </x-slot:actions>
</x-shipyard.app.form>

@endsection
