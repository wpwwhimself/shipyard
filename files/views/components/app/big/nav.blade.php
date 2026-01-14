@if (\App\Models\Shipyard\NavItem::visible()->count())
<x-shipyard.ui.button
    icon="menu"
    pop="Menu główne"
    action="none"
    onclick="openMenu('main')"
    class="tertiary"
/>
@endif

@if (Auth::user()?->hasRole("content-manager|technical"))
<x-shipyard.ui.button
    icon="cogs"
    pop="Administracja"
    action="none"
    onclick="openMenu('admin')"
    class="tertiary"
/>
@endif

<x-shipyard.app.big.menu />
