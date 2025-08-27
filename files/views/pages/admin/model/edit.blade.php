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
        action="#{{ $section['id'] }}"
    />
    @endforeach

    <x-shipyard.app.sidebar-separator />

    <x-shipyard.ui.button
        icon="check"
        pop="Zapisz zmiany"
        pop-direction="right"
        class="primary"
        action="none"
        onclick="submitShipyardForm()"
    />
    @if ($data)
    <x-shipyard.ui.button
        icon="trash"
        pop="Usuń wpis"
        pop-direction="right"
        class="danger"
        action="none"
        onclick="submitShipyardForm('delete')"
    />
    @endif
    <x-shipyard.ui.button
        icon="arrow-left"
        pop="Wróć"
        pop-direction="right"
        :action="Auth::user()->hasRole('technical')
            ? route('admin.model.list', ['model' => $scope])
            : route('profile')"
    />

    @if ($data)
    @foreach ($actions as $action)
    @if (isset($action["role"]) && !auth()->user()->hasRole($action["role"])) @continue @endif
    <x-shipyard.ui.button :action="route($action['route'], ['id' => $data->id])" :icon="$action['icon']" class="phantom {{ ($action['dangerous'] ?? false) ? 'danger' : '' }}">{{ $action['label'] }}</x-shipyard.ui.button>
    @endforeach
    @endif
</div>

@endsection

@section("content")

<x-shipyard.app.form :action="route('admin.model.edit.process', ['model' => $scope])" method="POST">
    <input type="hidden" name="id" value="{{ $data?->id ?? "" }}">

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
                    <i class="fas fa-user-plus" @popper(Twórca)></i>
                    {{ $data->creator->name }},
                    <span {{ Popper::pop($data->created_at) }}>{{ $data->created_at->diffForHumans() }}</span>
                </span>

                @if ($data->created_at != $data->updated_at)
                <span>
                    <i class="fas fa-user-pen" @popper(Ostatnia edycja)></i>
                    {{ $data->editor->name }},
                    <span {{ Popper::pop($data->updated_at) }}>{{ $data->updated_at->diffForHumans() }}</span>
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
                :options="$rdata['model']::all()->pluck('name', 'id')->toArray()"
                empty-option
            />
            @break

            @case ("many")
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
            @break
        @endswitch
    </x-shipyard.app.card>
    @endforeach
</x-shipyard.app.form>

@endsection
