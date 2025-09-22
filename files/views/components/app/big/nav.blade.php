@foreach (\App\Models\Shipyard\NavItem::visible()->get() as $page)
<x-shipyard.ui.button
    :icon="$page->icon"
    :label="$page->name"
    :action="$page->action"
    :show-for="$page->roles->pluck('name')->join('|') ?: null"
/>
@endforeach

<x-shipyard.ui.button
    icon="folder"
    pop="Pliki"
    :action="route('files')"
    show-for="content-manager"
/>

<x-shipyard.ui.button
    icon="database"
    pop="Zarządzanie modelami"
    :action="route('admin.models')"
    show-for="technical"
/>
