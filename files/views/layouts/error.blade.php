@extends("layouts.shipyard.admin")

@section("content")

<div class="card" style="text-align: center;">
    <h1 style="font-size: 3em; color: var(--error);">
        {{ $exception->getStatusCode() }} | @yield("title")
    </h1>

    <p>
        @yield("description")
    </p>

    <script>console.error("⚠️", "{{ $exception->getMessage() }}");</script>
</card>

@endsection
