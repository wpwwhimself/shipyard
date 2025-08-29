@auth
<x-shipyard.ui.button
    icon="user"
    label="Profil"
    :action="route('profile')"
/>
@if (Auth::user()->hasRole("technical"))
<x-shipyard.ui.button
    icon="gears"
    pop="Ustawienia systemu"
    :action="route('admin.system-settings')"
/>
@endif
<x-shipyard.ui.button
    icon="right-from-bracket"
    pop="Wyloguj"
    :action="route('logout')"
    class="danger"
/>

@else
<x-shipyard.ui.button
    icon="right-to-bracket"
    :label="implode('/', array_filter([
        'Logowanie',
        nullif('Rejestracja', setting('users_self_register_enabled')),
    ]))"
    :action="route('login')"
/>

@endauth
