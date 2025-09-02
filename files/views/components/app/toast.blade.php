@session("toast")
<div id="toast" class="{{ session("toast")[0] }}">
    @php
    $icons = [
        "success" => "check",
        "warning" => "exclamation",
        "error" => "close",
    ];
    @endphp
    @svg("mdi-".$icons[session("toast")[0]])

    <span role="label">
        {{ session("toast")[1] }}
    </span>
</div>
@endsession
