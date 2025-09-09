@session("toast")
<div id="toast" class="{{ session("toast")[0] }}">
    @php
    $icons = [
        "success" => "check",
        "warning" => "exclamation",
        "error" => "close",
    ];
    @endphp
    <x-shipyard.app.icon :name="$icons[session('toast')[0]]" />

    <span role="label">
        {{ session("toast")[1] }}
    </span>
</div>
@endsession
