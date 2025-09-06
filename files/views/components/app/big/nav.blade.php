<nav role="nav">
    @foreach (\App\Models\Shipyard\StandardPage::visible()->get() as $page)
    <x-shipyard.ui.button
        :icon="$page::META['icon']"
        :label="$page->name"
        :action="route('standard-page', ['slug' => $page->slug])"
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
