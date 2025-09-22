@extends("layouts.shipyard.admin")

@section("sidebar")

<div class="card stick-top">
    <x-shipyard.ui.button
        icon="wizard-hat"
        label="Zaklęcia"
        :action="route('docs.view', ['slug' => 'spells'])"
        show-for="archmage"
    />

    @foreach ($docs ?? [] as $doc_data)
    <x-shipyard.ui.button
        :label="$doc_data['title']"
        :action="route('docs.view', ['slug' => $doc_data['slug']])"
    />
    @endforeach
</div>

@endsection
