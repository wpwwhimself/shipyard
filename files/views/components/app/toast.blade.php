<div id="toast">
    @php
    $icons = [
        "success" => "check",
        "danger" => "exclamation",
        "error" => "close",
        "info" => "information",
    ];
    @endphp
    @foreach ($icons as $mode => $icon)
    <x-shipyard::app.icon :data="$mode" :name="$icon" />
    @endforeach

    <span role="label"></span>
</div>

@session("toast")
<script defer>
popToast(`{{ session("toast")[0] }}`, `{{ session("toast")[1] }}`);
</script>
@endsession
