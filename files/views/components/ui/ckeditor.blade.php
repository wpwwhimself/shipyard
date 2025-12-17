@props([
    "name",
    "value" => null,
])

<div class="main-container">
    <div class="editor-container editor-container_classic-editor editor-container_include-style" id="editor-container">
        <div class="editor-container__editor">
            <textarea name="{{ $name }}" {{ $attributes->class(["ckeditor"]) }}>
                {!! $value !!}
            </textarea>
        </div>
    </div>
</div>
