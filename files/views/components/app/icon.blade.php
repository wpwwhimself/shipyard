@props([
    "name" => null,
    "mode" => "mdi",
    "data" => null,
])

@php
if ($attributes->has("class")) {
    $data["class"] = implode(" ", ["icon", $attributes->get("class")]);
}
@endphp

@switch ($mode)
    @case ("url")
    <img class="icon invert-when-dark" src="{{ $data }}" alt="{{ $name }}">
    @break

    @default
    @php
    if (!file_exists(base_path("vendor/postare/blade-mdi/resources/svg/$name.svg"))) {
        $name = "help";
    }
    @endphp

    @svg("mdi-$name", $data ?? [])

@endswitch
