@auth
<x-shipyard.ui.button
    icon="account"
    label="Profil"
    :action="route('profile')"
/>
@if (Auth::user()->hasRole("technical"))
<x-shipyard.ui.button
    icon="cog"
    pop="Ustawienia systemu"
    :action="route('admin.system-settings')"
/>
@endif
<x-shipyard.ui.button
    icon="logout"
    pop="Wyloguj"
    :action="route('logout')"
    class="danger"
/>

@else
<x-shipyard.ui.button
    icon="login"
    :label="implode('/', array_filter([
        'Logowanie',
        nullif('Rejestracja', setting('users_self_register_enabled')),
    ]))"
    :action="route('login')"
/>

@endauth
