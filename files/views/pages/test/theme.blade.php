@extends("layouts.shipyard.admin")
@section("title", "Strona testowa dla motywu")

@section("content")

<x-shipyard.app.h lvl="1" icon="hand-wave">Hello</x-shipyard.app.h>

<x-shipyard.app.section
    title="Inputy"
    icon="cursor-text"
    :extended="true"
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
        :extended="true"
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

@endsection