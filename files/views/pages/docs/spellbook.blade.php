@extends("layouts.shipyard.docs")
@section("title", "Zaklęcia")
@section("subtitle", "Dokumentacja")

@section("content")

<x-shipyard.app.card
    title="Lista zaklęć"
    icon="wizard-hat"
>
    @foreach ($spells as $name => $props)
    <x-shipyard.app.h lvl="3">{{ $props["route"] }}</x-shipyard.app.h>
    {!! \Illuminate\Mail\Markdown::parse(preg_replace("/ +/", " ", $props["description"])) !!}

    @endforeach
</x-shipyard.app.card>

@endsection
