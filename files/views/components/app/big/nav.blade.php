<nav role="nav">
    @foreach (similar_models() as $model)
    <x-shipyard.ui.button
        :icon="$model['icon'] ?? null"
        :label="$model['label']"
        :action="route('admin.model.list', ['model' => $model['scope']])"
    />
    @endforeach
</nav>
