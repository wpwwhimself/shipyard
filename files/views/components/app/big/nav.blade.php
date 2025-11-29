@php
$pages = \App\Models\Shipyard\NavItem::visible()->get();
@endphp

@foreach ($pages as $page)
<x-shipyard.ui.button
    :icon="$page->icon"
    :pop="$page->name"
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
    :icon="model_icon('standard-pages')"
    :pop="model('standard-pages')::META['label']"
    :action="route('admin.model.list', ['model' => 'standard-pages'])"
    show-for="content-manager"
/>

<x-shipyard.ui.button
    icon="database"
    pop="Zarządzanie modelami"
    :action="route('admin.models')"
    show-for="technical"
/>
