@extends("layouts.shipyard.mail")
@section("title", "Resetowanie hasła")

@section("content")

<div class="card">
    <p>
        Dla tego adresu email została utworzona prośba o zresetowanie hasła.
        Aby tego dokonać, kliknij poniższy przycisk.
    </p>

    <x-shipyard.ui.button
        icon="key-change"
        label="Ustaw nowe hasło"
        :action="$url"
        class="primary"
    />
</div>

@endsection
