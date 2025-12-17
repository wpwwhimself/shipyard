@extends("layouts.shipyard.admin")

@section("sidebar")

<div class="card">
    <x-shipyard.ui.button
        icon="account"
        action="/"
        pop="hello"
    />
</div>

@endsection

@section("content")

<div class="card">
    <h2 class="stagger" style="--stagger-index: 1;">Your Shipyard starter kit is ready to go!</h2>

    <p class="stagger" style="--stagger-index: 2;">You can now start doing things:</p>
    <ul>
        <li class="stagger" style="--stagger-index: 3;">a thing,</li>
        <li class="stagger" style="--stagger-index: 4;">a thing,</li>
        <li class="stagger" style="--stagger-index: 5;">a thing,</li>
        <li class="stagger" style="--stagger-index: 6;">a thing,</li>
        <li class="stagger" style="--stagger-index: 7;">...</li>
    </ul>
</div>

@endsection
