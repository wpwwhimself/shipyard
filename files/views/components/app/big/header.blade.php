<header>
    @isset ($top)
    <div role="top-part">
        {{ $top }}
    </div>
    @endisset

    @isset ($middle)
    <div role="middle-part">
        {{ $middle }}
    </div>
    @endisset

    @isset ($bottom)
    <div role="bottom-part">
        {{ $bottom }}
    </div>
    @endisset
</header>
