@extends("layouts.shipyard.admin")

@section("sidebar")

<div class="card">
    <x-shipyard.ui.button
        icon="user"
        action="/"
        pop="hello"
    />
</div>

@endsection

@section("content")

<div class="card">
    <h2>Your Shipyard starter kit is ready to go!</h2>
    
    <p>You can now start doing things:</p>
    <ul>
        <li>a thing,</li>
        <li>a thing,</li>
        <li>a thing,</li>
        <li>a thing,</li>
        <li>...</li>
    </ul>
</div>

@endsection
