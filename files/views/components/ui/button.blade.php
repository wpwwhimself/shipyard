@props([
    "action" => null,
    "label" => null,
    "icon" => null,
    "hideLabel" => false,
    "iconRight" => false,
    "pop" => null,
    "badge" => null,
    "download" => null,
])

@if ($action == null)
<button disabled

@elseif ($action == "submit")

<button type="submit"

@elseif ($action == "none")
<span {{ $attributes->class("button") }}

@else
<a href="{{ $download ? route("file-download", ["path" => urlencode($action)]) : $action }}"
@endif

    {{ $attributes->merge(["class" => "button"]) }}

    @if ($pop || $hideLabel)
    {{ Popper::pop($pop ?? $label) }}
    @endif
>
    @if ($icon && !$iconRight) <i class="fas fa-{{ $icon }}"></i> @endif
    @if (!$hideLabel) <span>{{ $label }}</span> @endif
    @if ($icon && $iconRight) <i class="fas fa-{{ $icon }}"></i> @endif

    @if ($badge) <strong class="badge">{{ $badge }}</strong> @endif

    @if ($slot) {{ $slot }} @endif

@if (in_array($action, ["submit"]) || $action == null)
</button>

@elseif ($action == "none")
</span>

@else
</a>
@endif
