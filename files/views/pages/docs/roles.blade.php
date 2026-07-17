@extends("shipyard::layouts.docs")
@section("title", "Role")
@section("subtitle", "Dokumentacja")

@section("content")

<div id="docs">
    <div role="doc-contents"
        @class([
            "flex down",
            "stagger-contents" => setting("animations_mode") >= 1,
        ])
    >
        <x-shipyard::app.card
            title="Wprowadzenie"
            icon="key-chain"
            title-lvl="2"
            :inner-class="implode(' ', array_filter([
                setting('animations_mode') >= 2 ? 'stagger-contents' : null,
            ]))"
        >
            <p>Role uprawniają użytkownika do przeglądania określonych treści i wykonywania pewnych operacji.</p>
            <p>Aby uzyskać jakąś rolę, zgłoś się do administratora.</p>
        </x-shipyard::app.card>

        <x-shipyard::app.card
            title="Lista dostępnych ról"
            icon="key-chain"
            title-lvl="2"
            :inner-class="implode(' ', array_filter([
                setting('animations_mode') >= 2 ? 'stagger-contents' : null,
            ]))"
        >
            @foreach ($roles as $role)
            <x-shipyard::app.h lvl="2" :icon="$role['icon']">{{ $role["name"] }}</x-shipyard::app.h>
            <p>{{ $role["description"] }}</p>
            @endforeach
        </x-shipyard::app.card>
    </div>
</div>

@endsection
