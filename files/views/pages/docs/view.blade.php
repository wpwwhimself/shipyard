@extends("layouts.shipyard.docs")
@section("title", $title)
@section("subtitle", "Dokumentacja")

@section("content")

<div @class(["card", "stagger" => setting("animations_mode") >= 1, "stagger-contents" => setting("animations_mode") >= 2])>
    {!! \Illuminate\Mail\Markdown::parse($doc) !!}
</div>

@endsection
