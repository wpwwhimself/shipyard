@extends("layouts.shipyard.admin")
@section("title", $page->name)

@section("content")

<div class="card">
    {!! $page->content !!}
</div>

@endsection
