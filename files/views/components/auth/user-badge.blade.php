@auth


@else
<x-shipyard.ui.button
    icon="right-to-bracket"
    label="Zaloguj się"
    :action="route('login')"
/>

@endauth
