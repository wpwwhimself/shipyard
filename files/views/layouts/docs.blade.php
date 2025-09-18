@extends("layouts.shipyard.admin")

@section("sidebar")

<div class="card stick-top">
    @if (auth()->user()->hasRole("archmage"))
    <x-shipyard.ui.button
        icon="wizard-hat"
        label="Zaklęcia"
        :action="route('docs.view', ['slug' => 'spells'])"
    />
    @endif

    @foreach ($docs ?? [] as $doc_data)
    <x-shipyard.ui.button
        :label="$doc_data['title']"
        :action="route('docs.view', ['slug' => $doc_data['slug']])"
    />
    @endforeach
</div>

@endsection
