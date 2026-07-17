@extends("shipyard::layouts.admin")

@section("sidebar")

<div class="card stick-top">
    <x-shipyard::ui.button
        icon="wizard-hat"
        pop="Zaklęcia"
        :action="route('docs.view', ['slug' => 'zaklecia'])"
        show-for="spellcaster"
    />

    <x-shipyard::ui.button
        icon="key-chain"
        pop="Role"
        :action="route('docs.view', ['slug' => 'role'])"
    />

    <x-shipyard::app.sidebar-separator />

    @foreach ($docs ?? [] as $doc_data)
    <x-shipyard::ui.button
        :pop="$doc_data['title']"
        :icon="$doc_data['icon'] ?? 'book-outline'"
        :action="route('docs.view', ['slug' => $doc_data['slug']])"
    />
    @endforeach
</div>

@endsection
