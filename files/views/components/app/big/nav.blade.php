@php
$pages = \App\Models\Shipyard\NavItem::visible()->get()
    ->filter(fn ($page) => Auth::user()?->hasRole($page->roles->pluck('name')->join('|') ?: null))
    ->values();
$max_pages = (int) setting("menu_nav_items_in_top_bar_count") ?? INF;
$pages_are_truncated = $pages->count() > $max_pages;
@endphp

@if ($pages->count() > 0)
@foreach ($pages as $page_i => $page)
<x-shipyard.ui.button
    :icon="$page->icon"
    :pop="$page->name"
    :action="$page->action"
    {{-- :show-for="$page->roles->pluck('name')->join('|') ?: null" --}}
    :data-mode="$page_i < $max_pages
        ? 'pinned|main'
        : 'main'
    "
    :class="$page_i < $max_pages
        ? ''
        : 'hidden'
    "
/>
@endforeach
@if ($pages_are_truncated)
<x-shipyard.ui.button
    icon="menu"
    pop="Menu"
    action="none"
    onclick="openMenu('main')"
    class="tertiary"
    data-mode="pinned|admin"
/>
@endif
@endif

@if (Auth::user()?->hasRole("content-manager|technical"))
<x-shipyard.ui.button
    icon="cogs"
    pop="Administracja"
    action="none"
    onclick="openMenu('admin')"
    class="tertiary"
    data-mode="pinned|main"
/>
@endif

<x-shipyard.ui.button
    icon="folder"
    pop="Pliki"
    :action="route('files')"
    show-for="content-manager"
    class="hidden"
    data-mode="admin"
/>
<x-shipyard.ui.button
    :icon="model_icon('standard-pages')"
    :pop="model('standard-pages')::META['label']"
    :action="route('admin.model.list', ['model' => 'standard-pages'])"
    show-for="content-manager"
    class="hidden"
    data-mode="admin"
/>
<x-shipyard.ui.button
    :icon="model_icon('settings')"
    :pop="model('settings')::META['label']"
    :action="route('admin.system-settings')"
    show-for="technical"
    class="hidden"
    data-mode="admin"
/>
<x-shipyard.ui.button
    icon="database"
    pop="Zarządzanie danymi"
    :action="route('admin.models')"
    show-for="technical"
    class="hidden"
    data-mode="admin"
/>

<x-shipyard.ui.button
    icon="unfold-less-vertical"
    pop="Zwiń"
    action="none"
    onclick="openMenu('pinned')"
    class="tertiary hidden"
    data-mode="main|admin"
/>
