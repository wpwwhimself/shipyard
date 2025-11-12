@props([

])

<form {{ $attributes->merge([

]) }}>
    @if (strtolower($attributes->get("method")) == "post")
    @csrf
    @endif

    {{ $slot }}

    @isset($actions)
    <div class="actions">
        {{ $actions }}
    </div>
    @endisset
</form>

<script>
function submitShipyardForm(method = "save", method_name = "method")
{
    const form = document.forms[0];

    const hidden_input = document.createElement("input");
    hidden_input.type = "hidden";
    hidden_input.name = method_name;
    hidden_input.value = method;
    form.appendChild(hidden_input);

    form.submit();
}
</script>
