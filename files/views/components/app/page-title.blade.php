<div id="page-title">
    <h1 role="title">
        @isset ($title)
        {{ $title }}
        @endisset
    </h1>

    @isset ($subtitle)
    <h2 role="subtitle">
        {{ $subtitle }}
    </h2>
    @endisset
</div>
