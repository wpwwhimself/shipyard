@props([
    "model",
])

@if ($model && isset($model->created_at))
<div class="ghost">
    {{-- todo przerobić to i podobne na komponenty --}}
    <x-shipyard.app.icon-label-value
        icon="account-plus"
        label="Utworzono"
    >
        @isset ($model->created_by)
        {{ $model->creator->name }},
        @endisset
        <span {{ Popper::pop($model->created_at ?? "") }}>{{ $model->created_at?->diffForHumans() }}</span>
    </x-shipyard.app.icon-label-value>

    @if (isset($model->updated_at) && $model->created_at != $model->updated_at)
    <x-shipyard.app.icon-label-value
        icon="account-edit"
        label="Ostatnia edycja"
    >
        @isset ($model->updated_by)
        {{ $model->editor->name }},
        @endisset
        <span {{ Popper::pop($model->updated_at ?? "") }}>{{ $model->updated_at?->diffForHumans() }}</span>
    </x-shipyard.app.icon-label-value>
    @endif
</div>
@endif
