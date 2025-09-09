@props([
    "name",
    "data" => [],
])

@php
if (!file_exists(base_path("vendor/postare/blade-mdi/resources/svg/$name.svg"))) {
    $name = "help";
}
@endphp

@svg("mdi-$name", $data)
