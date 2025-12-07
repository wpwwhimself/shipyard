@extends("layouts.shipyard.admin")
@section("title", "Strona testowa dla motywu")

@section("content")

<x-shipyard.app.h lvl="1" icon="hand-wave">Hello</x-shipyard.app.h>

<x-shipyard.app.section
    title="Inputy"
    icon="cursor-text"
    :extended="false"
>
    <x-slot:actions>
        <x-shipyard.ui.button
            action="none"
            class="tertiary"
            icon="plus"
            label="Dodaj"
        />
    </x-slot:actions>

    <div class="flex down">
        @foreach ([
            ["text"],
            ["dummy-text"],
            ["TEXT"],
            ["HTML"],
            ["number"],
            ["select"],
            ["checkbox"],
        ] as [$type])
        <x-shipyard.ui.input :type="$type"
            :name="$type"
            :label="$type"
            icon="cursor-text"
            :select-data="[
                'options' => [
                    ['label' => 'jeden', 'value' => 1],
                    ['label' => 'dwa', 'value' => 2],
                    ['label' => 'trzy', 'value' => 3],
                ],
                'emptyOption' => 'bla bla',
            ]"
            value=" Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac arcu sit amet nunc egestas tincidunt a at felis. Aliquam nec tellus et augue pharetra fermentum finibus ac tellus. Nulla tempus urna in erat accumsan, vel luctus nulla dictum. Cras sit amet laoreet libero. Duis augue libero, feugiat at tempus nec, porta id tellus. Aliquam id ligula vel nunc pulvinar dapibus. Nullam eget massa convallis, porta lectus eu, gravida lacus.

            Nulla viverra dignissim volutpat. Ut faucibus ipsum quis turpis convallis cursus. Sed turpis nulla, elementum id tincidunt lobortis, molestie viverra sapien. Vestibulum accumsan efficitur orci a sodales. Vestibulum et elementum dui, in rutrum est. Mauris ex metus, efficitur in dui vel, suscipit eleifend nisi. Donec egestas lectus tellus, sit amet vestibulum lorem porttitor nec. Fusce sollicitudin posuere diam at venenatis."
        />
        @endforeach
    </div>
</x-shipyard.app.section>

<div class="grid but-mobile-down" style="--col-count: 2;">
    <x-shipyard.app.section
        title="Jestem sekcją"
        subtitle="I robię fajne rzeczy"
        icon="human-greeting"
        :extended="false"
    >
        <p>
            Jestem sekcją i jestem głównym elementem rozdzielającym rzeczy na stronie.
            Mogę być zwinięta, żeby nie zajmować tyle miejsca.
        </p>

        <x-shipyard.app.card
            title="Jestem kartą wewnątrz sekcji"
            icon="human-greeting"
        >
            <p>Jestem kartą wewnątrz sekcji - grupuję informacje.</p>
        </x-shipyard.app.card>
    </x-shipyard.app.section>

    <x-shipyard.app.card
        title="Jestem kartą"
        subtitle="I też robię fajne rzeczy"
        icon="human-greeting"
    >
        <p>
            Jestem kartą i jestem po to, by grupować informacje.
            Mogę znajdować się luzem na stronie lub być częścią sekcji.
        </p>
    </x-shipyard.app.card>
</div>

<x-shipyard.app.card
    title="Buttony i tosty"
    icon="button-pointer"
>
    <div class="flex right center middle">
        <x-shipyard.ui.button
            icon="check"
            label="Zapisz"
            pop="Główna akcja w systemie; po jej wykonaniu stanie się coś dużego (np. zapisanie danych, przeładowanie tej samej strony)."
            class="primary"
            :action="route('theme.test.toast', ['type' => 'success'])"
        />
        <x-shipyard.ui.button
            icon="alert"
            label="Uruchom"
            pop="Destrukcyjna akcja, wymagająca podwójnego zatwierdzenia."
            class="danger"
            :action="route('theme.test.toast', ['type' => 'success'])"
        />
        <x-shipyard.ui.button
            icon="arrow-right"
            label="Przejdź"
            pop="Podstawowy przycisk; po jego kliknięciu przejdziesz do innej strony."
            :action="route('theme.test.toast', ['type' => 'error'])"
        />
        <x-shipyard.ui.button
            icon="party-popper"
            label="Wywołaj"
            pop="Akcja niedestrukcyjna; po jego kliknięciu zmieni się coś na obecnie wyświetlanej stronie lub pojawi modal, bez odświeżania danych."
            action="none"
            onclick="openModal('test-modal')"
            class="tertiary"
        />
        <x-shipyard.ui.button
            icon="open-in-new"
            label="Otwórz"
            pop="Przejście do innej witryny lub wyświetlenie zawartości w nowej karcie. Wymaga cechy 'target'"
            :action="route('theme.test.toast', ['type' => 'success'])"
            target="_blank"
        />
        <x-shipyard.ui.button
            icon="toggle-switch"
            label="Przełącz"
            pop="Przełącznik; z założenia tertiary."
            action="none"
            onclick="toggleSwitch(this)"
            class="toggle"
        />
    </div>
</x-shipyard.app.card>

<script>
function toggleSwitch(btn)
{
    btn.classList.toggle("active");
}
</script>

@endsection
