@extends("layouts.shipyard.admin")

@section("sidebar")

<div class="card stick-top">
    <x-shipyard.ui.button
        icon="wizard-hat"
        pop="Zaklęcia"
        :action="route('docs.view', ['slug' => 'spells'])"
        show-for="spellcaster"
    />

    @foreach ($docs ?? [] as $doc_data)
    <x-shipyard.ui.button
        :pop="$doc_data['title']"
        :icon="$doc_data['icon'] ?? 'book-outline'"
        :action="route('docs.view', ['slug' => $doc_data['slug']])"
    />
    @endforeach
</div>

@endsection
