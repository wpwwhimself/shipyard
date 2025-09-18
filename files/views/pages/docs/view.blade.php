@extends("layouts.shipyard.docs")
@section("title", $title)
@section("subtitle", "Dokumentacja")

@section("content")

<div class="card">
    {!! \Illuminate\Mail\Markdown::parse($doc) !!}
</div>

@endsection
