@php
$pages = \App\Models\Shipyard\NavItem::visible()->get();
@endphp

<nav id="menu" class="hidden" data-mode="">
    @foreach ($pages as $page)
    <x-shipyard.ui.button
        :icon="$page->icon"
        :pop="$page->name"
        :action="$page->action"
        :show-for="$page->roles->pluck('name')->join('|') ?: null"
        data-mode="main"
    />
    @endforeach
    <x-shipyard.ui.button
        icon="menu"
        pop="Menu główne"
        action="none"
        onclick="openMenu('main')"
        class="tertiary"
        data-mode="admin"
    />

    <x-shipyard.ui.button
        icon="folder"
        pop="Pliki"
        :action="route('files')"
        show-for="content-manager"
        data-mode="admin"
    />
    <x-shipyard.ui.button
        :icon="model_icon('standard-pages')"
        :pop="model('standard-pages')::META['label']"
        :action="route('admin.model.list', ['model' => 'standard-pages'])"
        show-for="content-manager"
        data-mode="admin"
    />
    <x-shipyard.ui.button
        :icon="model_icon('settings')"
        :pop="model('settings')::META['label']"
        :action="route('admin.system-settings')"
        show-for="technical"
        data-mode="admin"
    />
    <x-shipyard.ui.button
        icon="database"
        pop="Zarządzanie danymi"
        :action="route('admin.models')"
        show-for="technical"
        data-mode="admin"
    />
    <x-shipyard.ui.button
        icon="cogs"
        pop="Administracja"
        action="none"
        onclick="openMenu('admin')"
        class="tertiary"
        data-mode="main"
    />

    <x-shipyard.ui.button
        icon="close"
        pop="Zamknij"
        action="none"
        onclick="openMenu('')"
        class="tertiary"
        data-mode="main|admin"
    />
</nav>
