@extends("layouts.shipyard.docs")
@section("title", $title)
@section("subtitle", "Dokumentacja")

@section("content")

<div id="doc">
    @if ($headings)
    <div class="flex down stick-top but-mobile-reset stagger-contents" role="doc-toc">
        <x-shipyard.app.card
            title="Spis treści"
            icon="table-of-contents"
        >
            {!! \Illuminate\Mail\Markdown::parse($headings) !!}
        </x-shipyard.app.card>

        @if ($models)
        <x-shipyard.app.card
            title="Powiązane modele"
            icon="database"
            inner-class="flex down no-gap"
        >
            @foreach ($models as $scope)
            <x-shipyard.ui.button
                :label="model($scope)::META['label']"
                :icon="model_icon($scope)"
                :action="route('admin.model.list', ['model' => $scope])"
                :role="model($scope)::META['role']"
            />
            @endforeach
        </x-shipyard.app.card>
        @endif
    </div>
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
document.querySelectorAll(`[role="doc-contents"] [role="card-title"], [role="doc-contents"] .contents > *`).forEach(el => {
    if (el.tagName.charAt(0) !== "H") return;

    heading_i++;
    el.id = `dh-${heading_i}`;
});

// add navigation to toc
document.querySelectorAll(`[role="doc-toc"] li`).forEach((li, i) => {
    li.classList.add("interactive", "shift-right");
    li.onclick = (ev) => {
        ev.preventDefault();
        ev.stopPropagation();
        jumpTo(`#dh-${i + 1}`, "start");
    }
});
</script>
@endsection
