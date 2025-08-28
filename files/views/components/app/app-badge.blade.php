<div id="app-badge">
    <x-shipyard.app.logo />

    <div role="details">
        <h2 role="app-name"><a href="/">{{ setting("app_name") }}</a></h2>
        <span role="copyright">
            <a href="https://creativecommons.org/licenses/by-sa/3.0/pl/">
                <i class="fa-brands fa-creative-commons" aria-hidden="true"></i>
                <i class="fa-brands fa-creative-commons-by" aria-hidden="true"></i>
                <i class="fa-brands fa-creative-commons-sa" aria-hidden="true"></i>
            </a>
            {{ app_lifetime() }}
            <span role="author"><x-shipyard.app.wpww-tag /></span>
        </span>
    </div>
</div>
