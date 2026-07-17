@extends("shipyard::layouts.admin")
@section("title", $page->name)

@section("content")

<div @class(["card", "stagger" => setting("animations_mode") >= 1, "stagger-contents" => setting("animations_mode") >= 2])>
    {!! $page->content !!}
</div>

@endsection
