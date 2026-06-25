@extends("layouts.shipyard.docs")
@section("title", "Zaklęcia")
@section("subtitle", "Dokumentacja")

@section("content")

<x-shipyard.app.card
    title="Lista zaklęć"
    icon="wizard-hat"
    title-lvl="2"
    role="doc-contents"
    @class(["stagger" => setting("animations_mode") >= 1])
>
    <div @class(["flex", "down", "stagger-contents" => setting("animations_mode") >= 2])>
    @foreach ($spells as $name => $props)
    <x-shipyard.app.h lvl="2">{{ $props["route"] }}</x-shipyard.app.h>
    {!! \Illuminate\Mail\Markdown::parse(preg_replace("/ +/", " ", $props["description"])) !!}
    @endforeach
    </div>
</x-shipyard.app.card>

@endsection
