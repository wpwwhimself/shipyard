<table id="app-badge">
    <tr>
        <td>
            <x-shipyard.app.logo />
        </td>
        <td role="details">
            <h2 role="app-name"><a href="/">{{ setting("app_name") }}</a></h2>
            <span role="copyright">
                <a href="https://creativecommons.org/licenses/by-sa/4.0/deed.pl">
                    <span>CC BY-SA 4.0</span>
                </a>
                {{ app_lifetime() }}
            </span>
        </td>
    </tr>
</table>
