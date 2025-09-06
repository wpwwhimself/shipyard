@extends("layouts.shipyard.base")

@section("body")

<x-shipyard.app.big.header>
    <x-slot:middle>
        <h1 style="margin: 0;">@yield("title")</h1>
    </x-slot:middle>
</x-shipyard.app.big.header>

<div id="middle-wrapper">
    @hasSection("content")
    <main>
        @yield("content")
    </main>
    @endif
</div>

<x-shipyard.app.big.footer>
    <x-slot:bottom>
        <x-shipyard.app.app-badge />
    </x-slot:bottom>
</x-shipyard.app.big.footer>

@endsection
