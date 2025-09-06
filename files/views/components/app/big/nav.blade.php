<nav role="nav">
    @if (Auth::user()->hasRole("technical"))
    <x-shipyard.ui.button
        icon="database"
        label="Zarządzanie modelami"
        :action="route('admin.models')"
    />
    @endif
</nav>
