@props([
    "horizontal" => false,
    "notHidden" => false,
])

<div @class([
    "loader",
    "hidden" => !$notHidden,
    "horizontal" => $horizontal
])>
    <x-shipyard.app.logo :clickable="false" />

    @if ($horizontal)
    <span class="icon"></span>
    @else
    <x-shipyard.app.icon name="loading" />
    @endif
</div>
