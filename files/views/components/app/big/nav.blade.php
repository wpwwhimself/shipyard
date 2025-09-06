<nav role="nav">
    @auth
    @foreach (similar_models() as $model)
    @if (!auth()->user()->hasRole($model["role"] ?? null)) @continue @endif
    <x-shipyard.ui.button
        :icon="$model['icon'] ?? null"
        :label="$model['label']"
        :action="route('admin.model.list', ['model' => $model['scope']])"
    />
    @endforeach
    @endauth
</nav>
