@props([
    
])

<form {{ $attributes->merge([

]) }}>
    @csrf

    {{ $slot }}

    @isset($actions)
    <div class="actions">
        {{ $actions }}
    </div>
    @endisset
</form>