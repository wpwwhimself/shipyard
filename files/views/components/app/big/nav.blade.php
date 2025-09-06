<nav role="nav">
    @if (Auth::user()?->hasRole("content-manager"))
    <x-shipyard.ui.button
        icon="folder"
        label="Pliki"
        :action="route('files')"
    />
    @endif

    @if (Auth::user()?->hasRole("technical"))
    <x-shipyard.ui.button
        icon="database"
        label="Zarządzanie modelami"
        :action="route('admin.models')"
    />
    @endif
</nav>
