<div id="app-badge">
    <x-shipyard.app.logo />

    <div role="details">
        <h2 role="app-name"><a href="/">{{ setting("app_name") }}</a></h2>
        <span role="copyright">
            <span role="author" @popper(Projekt i wykonanie)><x-shipyard.app.wpww-tag /></span>
            <a href="https://creativecommons.org/licenses/by-sa/4.0/deed.pl" @popper(CC BY-SA 4.0)>
                <img class="icon invert-when-dark" src="https://creativecommons.org/wp-content/themes/vocabulary-theme/vocabulary/svg/cc/icons/cc-icons.svg#cc-logo" alt="cc"><img class="icon invert-when-dark" src="https://creativecommons.org/wp-content/themes/vocabulary-theme/vocabulary/svg/cc/icons/cc-icons.svg#cc-by" alt="by"><img class="icon invert-when-dark" src="https://creativecommons.org/wp-content/themes/vocabulary-theme/vocabulary/svg/cc/icons/cc-icons.svg#cc-sa" alt="sa">
            </a>
            <span role="app-lifetime">{{ app_lifetime() }}</span>
            <a href="https://github.com/wpwwhimself/shipyard"
                target="_blank"
                role="shipyard-version"
                class="hide-for-print"
                @popper(Wersja Shipyard)
            >
                <img class="icon invert-when-dark" src="{{ asset("media/Shipyard/shipyard.svg") }}" alt="shipyard logo">
                {{ shipyard_version() }}
            </a>
        </span>
    </div>
</div>
