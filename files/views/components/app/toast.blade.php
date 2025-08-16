@session("toast")
<div id="toast" class="{{ session("toast")[0] }}">
    @php
    $icons = [
        "success" => "check",
        "warning" => "exclamation",
        "error" => "times",
    ];
    @endphp
    <i class="fas fa-{{ $icons[session("toast")[0]] }}"></i>

    <span role="label">
        {{ session("toast")[1] }}
    </span>
</div>
@endsession
