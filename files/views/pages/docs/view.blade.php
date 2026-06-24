@extends("layouts.shipyard.docs")
@section("title", $title)
@section("subtitle", "Dokumentacja")

@section("content")

<div id="doc"
    class="flex right spread and-cover"
    style="flex-direction: row-reverse; align-items: flex-start;"
>
    @if ($headings)
    <x-shipyard.app.card role="doc-toc"
        title="Spis treści"
        icon="table-of-contents"
        class="stick-top but-mobile-reset"
    >
        {!! \Illuminate\Mail\Markdown::parse($headings) !!}
    </x-shipyard.app.card>
    @endif

    <div class="flex down">
        <x-shipyard.app.card
            role="doc-contents"
            :inner-class="implode(' ', array_filter([
                setting('animations_mode') >= 1 ? 'stagger' : null,
                setting('animations_mode') >= 2 ? 'stagger-contents' : null,
            ]))"
        >
            {!! \Illuminate\Mail\Markdown::parse($doc) !!}
        </x-shipyard.app.card>
    </div>
</div>

@endsection

@section("appends")
<script defer>
document.querySelectorAll(`[role="doc-toc"] li`).forEach(li => {
    li.classList.add("interactive", "shift-right");
    //todo add jumpers
});
</script>
@endsection
