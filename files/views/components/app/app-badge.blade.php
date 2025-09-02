<div id="app-badge">
    <x-shipyard.app.logo />

    <div role="details">
        <h2 role="app-name"><a href="/">{{ setting("app_name") }}</a></h2>
        <span role="copyright">
            <a href="https://creativecommons.org/licenses/by-sa/4.0/deed.pl">
                <img class="icon" src="https://creativecommons.org/wp-content/themes/vocabulary-theme/vocabulary/svg/cc/icons/cc-icons.svg#cc-logo" alt="cc">
                <img class="icon" src="https://creativecommons.org/wp-content/themes/vocabulary-theme/vocabulary/svg/cc/icons/cc-icons.svg#cc-by" alt="by">
                <img class="icon" src="https://creativecommons.org/wp-content/themes/vocabulary-theme/vocabulary/svg/cc/icons/cc-icons.svg#cc-sa" alt="sa">
                <span>CC BY-SA 4.0</span>
            </a>
            {{ app_lifetime() }}
            <span role="author"><x-shipyard.app.wpww-tag /></span>
        </span>
    </div>
</div>
