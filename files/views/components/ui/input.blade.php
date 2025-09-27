@props([
    'type' => 'text',
    'name',
    'label' => null,
    "hint" => null,
    "value" => null,
    'icon' => null,
    "selectData" => null,
    "columnTypes" => [],
    "autofillFrom" => null,
    "characterLimit" => null,
    "disabled" => false,
])

@php
$storageFile = $type == "storage_url";
if ($storageFile) $type = "url";

$autofillRoute = $autofillFrom
    ? route($autofillFrom[0], ['model_name' => $autofillFrom[1]])
    : null;

$extraButtons = ($type == "url" && $value) || $storageFile || ($type == "icon" && $value);
@endphp

<div {{
    $attributes
        // ->filter(fn($val, $key) => (!in_array($key, ["autofocus", "required", "placeholder", "small"])))
        ->merge(["for" => $name])
        ->class(["input-container"])
    }}>

    <span role="label-wrapper">
        @if ($icon)
        <x-shipyard.app.icon :name="$icon" />
        @endif

        @if($type != "hidden" && $label)
        <label for="{{ $name }}">{{ $label }}</label>
        @endif

        @if ($hint)
        <span role="hint" {{ Popper::pop($hint) }}>
            <x-shipyard.app.icon name="tooltip-question" />
        </span>
        @endif
    </span>

    @switch ($type)
        @case ("TEXT")
        <textarea name="{{ $name }}"
            id="{{ $name }}"
            placeholder="Zacznij pisać..."
            {{ $disabled ? "disabled" : "" }}
            {{ $attributes }}
        >{{ $value }}</textarea>
        @break

        @case ("HTML")
        <x-shipyard.ui.ckeditor :name="$name" :value="$value" />
        @break

        @case ("JSON")
        <input type="hidden" name="{{ $name }}" value="{{ $value ? json_encode($value) : null }}">
        <table data-name="{{ $name }}" data-columns="{{ count($columnTypes) }}">
            <thead>
                <tr>
                    @foreach (array_keys($columnTypes) as $key)
                    <th>{{ $key }}</th>
                    @endforeach
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($value ?? [] as $key => $val)
                <tr>
                    @php $i = 0; @endphp
                    @switch (count($columnTypes))
                        @case (2)
                        {{-- key-value array --}}
                        @foreach ($columnTypes as $t)
                        <td>
                            <input type="{{ $t }}" value="{{ ($i++ == 0) ? $key : $val }}" onchange="JSONInputUpdate('{{ $name }}')" {{ $disabled ? "disabled" : "" }} />
                        </td>
                        @endforeach
                        @break

                        @case (1)
                        {{-- simple array --}}
                        <td>
                            <input type="{{ current($columnTypes) }}" value="{{ $val }}" onchange="JSONInputUpdate('{{ $name }}')" {{ $disabled ? "disabled" : "" }} />
                        </td>
                        @break

                        @default
                        {{-- array of arrays --}}
                        @foreach ($columnTypes as $t)
                        @php
                        if (!isset($val[$i])) $val[$i] = null;
                        if ($t == "JSON") {
                            $t = "text";
                            $val[$i] = json_encode($val[$i]);
                        }
                        @endphp
                        <td>
                            <input type="{{ $t }}" {{ $t == "checkbox" && $val[$i] ? "checked" : "" }} value="{{ $val[$i++] }}" onchange="JSONInputUpdate('{{ $name }}')" {{ $disabled ? "disabled" : "" }} />
                        </td>
                        @endforeach
                    @endswitch

                    @if (!$disabled)
                    <td><span icon="delete" class="button tertiary" onclick="JSONInputDeleteRow('{{ $name }}', this)"><x-shipyard.app.icon name="delete" /></span></td>
                    @endif
                </tr>
                @endforeach
            </tbody>

            @unless ($disabled)
            <tfoot>
                <tr role="new-row">
                    @foreach ($columnTypes as $t)
                    <td>
                        <input type="{{ $t }}" onchange="JSONInputUpdate('{{ $name }}')"
                            onkeydown="JSONInputWatchForConfirm('{{ $name }}', event);"
                            onblur="JSONInputAddRow('{{ $name }}', )"
                            value="{{ $t == "checkbox" ? "1" : "" }}"
                        />
                    </td>
                    @endforeach

                    <td>
                        <span title="Dodaj" class="button secondary" onclick="JSONInputAddRow('{{ $name }}')"><x-shipyard.app.icon name="plus" /></span>
                        <span title="Usuń" class="button tertiary hidden" onclick="JSONInputDeleteRow('{{ $name }}', this)"><x-shipyard.app.icon name="delete" /></span>
                    </td>
                </tr>
            </tfoot>
            @endunless
        </table>
        @break

        @case ("select")
            @php
            if (isset($selectData["optionsFromScope"])) {
                [$model, $scope, $label, $value] = $selectData["optionsFromScope"];
                $selectData["options"] = $model::$scope()->get()->map(fn ($i) => [
                    "label" => $i->{$label},
                    "value" => $i->{$value},
                ])->toArray();
            }
            @endphp

            @if ($selectData["radio"] ?? false)
            <div role="radio-group">
                @foreach ($selectData["options"] ?? [] as $option)
                <label>
                    <input type="radio"
                        name="{{ $name }}"
                        value="{{ $option["value"] }}"
                        {{ $disabled ? "disabled" : "" }}
                        {{ $attributes }}
                    />
                    {{ $option["label"] }}
                </label>
                @endforeach
            </div>

            @else
            <select name="{{ $name }}"
                id="{{ $name }}"
                {{ $disabled ? "disabled" : "" }}
                {{ $attributes }}
            >
                @isset ($selectData["emptyOption"]) <option value="">– {{ ($selectData["emptyOption"] === true) ? "brak" : $selectData["emptyOption"] }} –</option> @endisset
                @foreach ($selectData["options"] ?? [] as ["value" => $opt_val, "label" => $opt_label])
                <option value="{{ $opt_val }}"
                    @if ($opt_val == $value) selected @endif
                >
                    {{ $opt_label }}
                </option>
                @endforeach
            </select>
            @endif
        @break

        @case ("checkbox")
        <input type="checkbox"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ $value ?? 1 }}"
            {{ $disabled ? "disabled" : "" }}
            {{ $attributes }}
        />
        @break

        @default
        <input type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $attributes->get("placeholder", "— brak —") }}"
            {{ $disabled ? "disabled" : "" }}
            {{ $attributes }}
        />
    @endswitch

    @if ($extraButtons)
    <div role="extra-buttons">
        @if ($storageFile)
        <x-shipyard.ui.button
            icon="folder-open"
            onclick="browseFiles('{{ route('files-list', ['select' => $name]) }}')"
        />
        @endif

        @if ($type == "url" && $value)
        <x-shipyard.ui.button
            :action="$value"
            icon="open-in-new"
            class="accent background secondary"
            target="_blank"
        />
        @endif

        @if ($type == "icon" && $value)
        <x-shipyard.app.icon :name="$value" />
        @endif
    </div>
    @endif

    @if ($characterLimit)
    <span role="character-count"></span>
    <script defer>
    const input = document.querySelector(`#{{ $name }}`)
    const counter = input.parentElement.parentElement.querySelector("[role='character-count']")
    const counter_content_template = `current / {{ $characterLimit }}`

    counter.textContent = counter_content_template.replace("current", input.textContent.length)

    input.addEventListener("input", (ev) => {
        if (ev.target.value.length > {{ $characterLimit }}) {
            ev.target.value = ev.target.value.slice(0, {{ $characterLimit }})
        }
        counter.textContent = counter_content_template.replace("current", ev.target.value.length)
    })
    </script>
    @endif
</div>

@if ($autofillFrom)
<script>
window.autofill = window.autofill ?? {}
fetch("{{ $autofillRoute }}").then(res => res.json()).then(data => {
    window.autofill['{{ $name }}'] = data
})
</script>
@endif
