@props([
    "model",
])

@if ($model && isset($model->created_by))
<div class="flex right ghost">
    {{-- todo przerobić to i podobne na komponenty --}}
    <span>
        <span @popper(Twórca)>@svg("mdi-account-plus")</span>
        {{ $model->creator->name }},
        <span {{ Popper::pop($model->created_at ?? "") }}>{{ $model->created_at?->diffForHumans() }}</span>
    </span>

    @if ($model->created_at != $model->updated_at)
    <span>
        <span @popper(Ostatnia edycja)>@svg("mdi-account-edit")</span>
        {{ $model->editor->name }},
        <span {{ Popper::pop($model->updated_at ?? "") }}>{{ $model->updated_at?->diffForHumans() }}</span>
    </span>
    @endif
</div>
@endif
