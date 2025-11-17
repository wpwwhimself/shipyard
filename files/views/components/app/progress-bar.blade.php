@props([
    "progress" => 0,
])

<div class="progress-bar" style="--progress: {{ $progress }}%">
    {{ $slot }}
</div>
