@extends("layouts.shipyard.admin")

@section("content")

<div class="card" style="text-align: center;">
    <h1 style="color: var(--error);">
        {{ $exception->getStatusCode() }} | @yield("title")
    </h1>

    <p>
        @yield("description")
    </p>

    <div class="flex right center middle but-mobile-down">
        <x-shipyard.ui.button
            icon="chevron-left"
            label="Powrót"
            action="none"
            onclick="history.back()"
        />
        <x-shipyard.ui.button
            icon="chevron-double-left"
            label="Do strony głównej"
            action="/"
        />

        @if (in_array($exception->getStatusCode(), [500, 400]))
        <x-shipyard.ui.button
            icon="bug"
            label="Zgłoś błąd"
            action="none"
            onclick="openModal('report-error', {
                user_email: '{{ Auth::user()?->email }}',
                url: '{{ url()->current() }}',
            })"
            class="tertiary"
        />
        @endif
    </div>

    <script>console.error("⚠️", "{{ $exception->getMessage() }}");</script>
</card>

@endsection
