@extends("layouts.shipyard.admin")
@section("title", "Ustawienia systemu")
@section("subtitle", "Administracja")

@section("sidebar")

<div class="card stick-top">
    @foreach ($fields as $section)
    <x-shipyard.ui.button
        :icon="$section['icon'] ?? null"
        :pop="$section['title']"
        pop-direction="right"
        class="tertiary"
        action="#{{ $section['id'] }}"
    />
    @endforeach

    <x-shipyard.app.sidebar-separator />

    <x-shipyard.ui.button
        icon="content-save"
        pop="Zapisz zmiany"
        pop-direction="right"
        class="primary"
        action="none"
        onclick="submitShipyardForm()"
    />

    <x-shipyard.app.sidebar-separator />

    @foreach (similar_models("settings") as $model)
    <x-shipyard.ui.button
        :icon="$model['icon'] ?? null"
        :pop="$model['label']"
        pop-direction="right"
        :action="route('admin.model.list', ['model' => $model['scope']])"
    />
    @endforeach
</div>

@endsection

@section("content")

<x-shipyard.app.form :action="route('admin.system-settings.process')" method="post">

@foreach ($fields as $section)
<x-shipyard.app.card
    :title="$section['title']"
    :subtitle="$section['subtitle'] ?? null"
    :icon="$section['icon'] ?? null"
    :id="$section['id'] ?? null"
>
    @foreach ($section["fields"] as $field)
        @isset ($field["subsection_title"])
        <x-shipyard.app.h lvl="3" :icon="$field['subsection_icon'] ?? null">{{ $field["subsection_title"] }}</x-shipyard.app.h>

            @isset ($field["columns"])
            <div class="grid" style="--col-count: {{ count($field["columns"]) }};">
                @foreach ($field["columns"] as $column)
                <x-shipyard.app.card
                    :title="$column['subsection_title']"
                    :subtitle="$column['subsection_subtitle'] ?? null"
                    :icon="$column['subsection_icon'] ?? null"
                    title-lvl="4"
                >
                    @foreach ($column["fields"] as $sub_field)
                    <x-shipyard.ui.input
                        :type="$settings->find($sub_field['name'])->type ?? null"
                        :name="$sub_field['name']"
                        :label="$sub_field['label']"
                        :hint="$sub_field['hint'] ?? null"
                        :icon="$sub_field['icon'] ?? null"
                        :select-data="$sub_field['select_data'] ?? null"
                        :value="$settings->find($sub_field['name'])->type == 'checkbox' ? null : setting($sub_field['name'])"
                        :checked="$settings->find($sub_field['name'])->type == 'checkbox' && setting($sub_field['name'])"
                    />
                    @endforeach
                </x-shipyard.app.card>
                @endforeach
            </div>
            @endisset

        @else
        <x-shipyard.ui.input
            :type="$settings->find($field['name'])->type ?? null"
            :name="$field['name']"
            :label="$field['label']"
            :hint="$field['hint'] ?? null"
            :icon="$field['icon'] ?? null"
            :select-data="$field['select_data'] ?? null"
            :value="$settings->find($field['name'])->type == 'checkbox' ? null : setting($field['name'])"
            :checked="$settings->find($field['name'])->type == 'checkbox' && setting($field['name'])"
        />
        @endisset
    @endforeach
</x-shipyard.app.card>
@endforeach

</x-shipyard.app.form>

@endsection
