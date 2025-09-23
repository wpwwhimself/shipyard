@props([
    "model",
])

@if ($model && isset($model->created_by))
<div class="ghost">
    {{-- todo przerobić to i podobne na komponenty --}}
    <x-shipyard.app.icon-label-value
        icon="account-plus"
        label="Twórca"
    >
        {{ $model->creator->name }},
        <span {{ Popper::pop($model->created_at ?? "") }}>{{ $model->created_at?->diffForHumans() }}</span>
    </x-shipyard.app.icon-label-value>

    @if ($model->created_at != $model->updated_at)
    <x-shipyard.app.icon-label-value
        icon="account-edit"
        label="Ostatnia edycja"
    >
        {{ $model->editor->name }},
        <span {{ Popper::pop($model->updated_at ?? "") }}>{{ $model->updated_at?->diffForHumans() }}</span>
    </x-shipyard.app.icon-label-value>
    @endif
</div>
@endif
