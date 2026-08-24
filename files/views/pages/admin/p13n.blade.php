@extends("shipyard::layouts.admin")
@section("title", "Personalizacja systemu")
@section("subtitle", "Administracja")

@section("sidebar")

<div class="card stick-top">
    <x-shipyard::ui.button
        icon="arrow-left"
        pop="Wróć"
        :action="route('profile')"
    />
</div>

@endsection

@section("content")

<x-shipyard::app.form :action="route('profile.p13n.process')" method="post" @class(["stagger-contents" => setting("animations_mode") >= 1])>
    <x-shipyard::app.card>
        <p>
            Poniższy panel pozwala na dopasowanie systemu do własnych preferencji.
            Wprowadzone zmiany nadpisują domyślne zachowanie aplikacji.
        </p>
    </x-shipyard::app.card>
    
    <x-shipyard::app.section
        title="Wygląd"
        subtitle="Kolory i zachowanie"
        icon="palette"
    >
        <x-shipyard::ui.input type="color"
            name="app_primary_color"
            label="Własny kolor akcentów"
            icon="palette"
            :value="$data->get('app_primary_color')"
        />
        <x-shipyard::ui.input type="select"
            name="app_adaptive_dark_mode"
            label="Tryb ciemny"
            icon="theme-light-dark"
            :select-data="[
                'options' => [
                    ['label' => 'Automatycznie – na podstawie ustawień przeglądarki/systemu', 'value' => 1],
                    ['label' => 'Ręcznie – przyciskiem na dole strony', 'value' => 0],
                ],
                'emptyOption' => 'Domyślnie',
            ]"
            :value="$data->get('app_adaptive_dark_mode')"
        />
    </x-shipyard::app.section>

    <x-slot:actions>
        <x-shipyard::app.card>
            <x-shipyard::ui.button
                icon="content-save"
                label="Zapisz zmiany"
                class="primary"
                action="submit"
            />
        </x-shipyard::app.card>
    </x-slot:actions>
</x-shipyard::app.form>

@endsection