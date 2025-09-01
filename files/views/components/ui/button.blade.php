@props([
    "action" => null,
    "label" => null,
    "icon" => null,
    "hideLabel" => false,
    "iconRight" => false,
    "pop" => null,
    "popDirection" => "top",
    "badge" => null,
    "download" => null,
])

@if ($action == null)
<button disabled

@elseif ($action == "submit")

<button type="submit"

@elseif ($action == "none")
<span {{ $attributes->class([
    "button",
    "active" => URL::current() == $action,
]) }}

@else
<a href="{{ $download ? route("file-download", ["path" => urlencode($action)]) : $action }}"
@endif

    {{ $attributes->class([
        "button",
        "active" => URL::current() == $action,
    ]) }}

    @if ($pop || $hideLabel)
    {{ Popper::position($popDirection)->pop($pop ?? $label) }}
    @endif
>
    @if ($icon && !$iconRight) @svg("mdi-".$icon) @endif
    @if ($label && !$hideLabel) <span>{{ $label }}</span> @endif
    @if ($icon && $iconRight) @svg("mdi-".$icon) @endif

    @if ($badge) <strong class="badge">{{ $badge }}</strong> @endif

    @if ($slot) {{ $slot }} @endif

@if (in_array($action, ["submit"]) || $action == null)
</button>

@elseif ($action == "none")
</span>

@else
</a>
@endif
