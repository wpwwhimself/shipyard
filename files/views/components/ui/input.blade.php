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

$dummy = Str::startsWith($type, "dummy-");
if ($dummy) $type = Str::after($type, "dummy-");

$lookup = $type == "lookup";
if ($lookup) $type = "text";

$extraButtons = ($type == "url" && $value) || $storageFile || ($type == "icon");

if ($type == "date") $value = ($value)
    ? Carbon\Carbon::parse($value)->format("Y-m-d")
    : null;
@endphp

<div {{
    $attributes
        ->filter(fn($val, $key) => !Str::startsWith($key, "on"))
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

        @if ($attributes->get("required"))
        <span class="accent error" @popper(Wymagane)>
            <x-shipyard.app.icon name="asterisk" />
        </span>
        @endif

        @if ($disabled)
        <span @popper(Zablokowane)>
            <x-shipyard.app.icon name="lock" />
        </span>
        @endif
    </span>

    @if ($dummy)
    <span role="dummy">
        @if ($value && in_array($type, ["TEXT", "HTML"]))
        {!! Illuminate\Mail\Markdown::parse($value) !!}
        @else
        {{ $value ?? "—" }}
        @endif
    </span>

    @else
    @switch ($type)
        @case ("TEXT")
        <textarea name="{{ $name }}"
            id="{{ $name }}"
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
                    <td>
                        <span class="button tertiary" onclick="JSONInputPrependRow('{{ $name }}', this)" @popper(Dodaj wiersz przed)><x-shipyard.app.icon name="table-row-plus-before" /></span>
                        <span class="button tertiary" onclick="JSONInputDeleteRow('{{ $name }}', this)" @popper(Usuń wiersz)><x-shipyard.app.icon name="table-row-remove" /></span>
                    </td>
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
                        <span class="button secondary" onclick="JSONInputAddRow('{{ $name }}')" @popper(Dodaj)><x-shipyard.app.icon name="plus" /></span>
                        <span class="button tertiary hidden" onclick="JSONInputPrependRow('{{ $name }}', this)" @popper(Dodaj wiersz przed)><x-shipyard.app.icon name="table-row-plus-before" /></span>
                        <span class="button tertiary hidden" onclick="JSONInputDeleteRow('{{ $name }}', this)" @popper(Usuń)><x-shipyard.app.icon name="table-row-remove" /></span>
                    </td>
                </tr>
            </tfoot>
            @endunless
        </table>
        @break

        @case ("select")
        @case ("select-multiple")
            @php
            if (isset($selectData["optionsFromScope"])) {
                [$model, $scope, $option_label, $option_value, $scope_args] = $selectData["optionsFromScope"] + array_fill(0, 5, null);
                $selectData["options"] = $model::$scope($scope_args)->get()->map(fn ($i) => [
                    "label" => $i->{$option_label ?? "option_label"},
                    "value" => $i->{$option_value ?? "id"},
                ])->toArray();
            }
            if (isset($selectData["optionsFromStatic"])) {
                [$model, $function_name, $option_label, $option_value, $function_args] = $selectData["optionsFromStatic"] + array_fill(0, 5, null);
                $selectData["options"] = $model::$function_name($function_args)->map(fn ($i) => [
                    "label" => $i[$option_label ?? "label"],
                    "value" => $i[$option_value ?? "value"],
                ])->toArray();
            }
            if (isset($selectData["optionsFromConst"])) {
                [$model, $const, $option_label, $option_value] = $selectData["optionsFromConst"] + array_fill(0, 4, null);
                $selectData["options"] = collect(constant($model."::".$const))->map(fn ($i) => [
                    "label" => $i[$option_label ?? "label"],
                    "value" => $i[$option_value ?? "value"],
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
                {{ Str::endsWith($type, "multiple") ? "multiple" : "" }}
                {{ $attributes }}
            >
                @isset ($selectData["emptyOption"]) <option value="">– {{ ($selectData["emptyOption"] === true) ? "brak" : $selectData["emptyOption"] }} –</option> @endisset

                @foreach ($selectData["options"] ?? [] as $optlabel => $defined_options)
                @php
                if (isset($defined_options["value"])) {
                    // one dimensional array - everything is contained in one group
                    $hasGroups = false;
                    $presented_options = [$defined_options];
                } else {
                    // two dimensional array - labels represent groups, options are nested
                    $hasGroups = true;
                    $presented_options = $defined_options;
                }
                @endphp

                @if ($hasGroups) <optgroup label="{{ $optlabel }}"> @endif
                @foreach ($presented_options ?? [] as $opt)
                <option value="{{ $hasGroups ? implode(":", [$opt["type"], $opt["value"]]) : $opt["value"] }}"
                    @if (is_array($value)
                        ? collect($value)->contains($opt["value"])
                        : ($hasGroups
                            // both sides have to be cast to string to maintain different types (1 = "1") but 3= keeps out false null sets (null = 0)
                            ? implode(":", [$opt["type"], $opt["value"]]) === (string) $value
                            : (string) $opt["value"] === (string) $value
                        )
                    ) selected @endif
                >
                    {{ $opt["label"] }}
                </option>
                @endforeach

                @if ($hasGroups) </optgroup> @endif
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
            {{ $attributes->merge([
                "oninput" => $lookup ? "lookup('".($selectData['dataRoute'] ? route($selectData['dataRoute']) : null)."', '".$selectData['dataRoute']."', this.value);" : (
                    $type == "icon" ? "getIconPreview('".$name."');" :
                    null
                ),
            ]) }}
        />
    @endswitch
    @endif

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

        @if ($type == "icon")
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

@if ($lookup)
<div id="lookup-container" for="{{ $selectData["dataRoute"] ?? null }}">
    <x-shipyard.app.loader horizontal />
    <div role="results"></div>
</div>
@endif

@if ($autofillFrom)
<script>
window.autofill = window.autofill ?? {}
fetch("{{ $autofillRoute }}").then(res => res.json()).then(data => {
    window.autofill['{{ $name }}'] = data
})

reapplyPopper();
</script>
@endif
