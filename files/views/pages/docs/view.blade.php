@extends("layouts.shipyard.docs")
@section("title", $title)
@section("subtitle", "Dokumentacja")

@section("content")

<div id="doc">
    @if ($headings)
    <x-shipyard.app.card role="doc-toc"
        title="Spis treści"
        icon="table-of-contents"
        class="stick-top but-mobile-reset"
    >
        {!! \Illuminate\Mail\Markdown::parse($headings) !!}
    </x-shipyard.app.card>
    @endif

    <div role="doc-contents"
        @class([
            "flex down",
            "stagger-contents" => setting("animations_mode") >= 1,
        ])
    >
        @foreach ($doc as $heading => $content)
        <x-shipyard.app.card :title="$heading"
            title-lvl="2"
            :icon="$icon ?? 'book-information-variant'"
            :inner-class="setting('animations_mode') >= 2 ? 'stagger-contents' : null"
        >
            {!! \Illuminate\Mail\Markdown::parse($content) !!}
        </x-shipyard.app.card>
        @endforeach
    </div>
</div>

@endsection

@section("appends")
<script defer>
// add targets to headings
let heading_i = 0;
document.querySelectorAll(`[role="doc-contents"] .contents > *`).forEach(el => {
    if (el.tagName.charAt(0) !== "H") return;

    heading_i++;
    el.id = `dh-${heading_i}`;
});

// add navigation to toc
document.querySelectorAll(`[role="doc-toc"] li`).forEach(li => {
    li.classList.add("interactive", "shift-right");
});
</script>
@endsection
