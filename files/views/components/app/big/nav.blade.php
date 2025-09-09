<nav role="nav">
    @foreach (\App\Models\Shipyard\NavItem::visible()->get() as $page)
    @if (!Auth::user()?->hasRole($page->roles->pluck("name")->join("|") ?: null)) @continue @endif
    <x-shipyard.ui.button
        :icon="$page->icon"
        :label="$page->name"
        :action="$page->action"
    />
    @endforeach

    @if (Auth::user()?->hasRole("content-manager"))
    <x-shipyard.ui.button
        icon="folder"
        pop="Pliki"
        :action="route('files')"
    />
    @endif

    @if (Auth::user()?->hasRole("technical"))
    <x-shipyard.ui.button
        icon="database"
        pop="Zarządzanie modelami"
        :action="route('admin.models')"
    />
    @endif
</nav>
