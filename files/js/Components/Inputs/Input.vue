<script setup lang="ts">
import Icon from '../Icon.vue';
import Button from '../Inputs/Button.vue';
import { ref } from 'vue';

let storageFile = ref(false)
let extraButtons = ref(false)

const props = withDefaults(defineProps<{
    name: string,
    label?: string,
    type?: string,
    value?: any,
    icon?: string,
    hint?: string,
    characterLimit?: number,

    options?: object,
    emptyOption?: string | false,
    columnTypes?: object,

    placeholder?: string,
    required?: boolean,
    autofocus?: boolean,
    disabled?: boolean,
    checked?: boolean,
    autocomplete?: string,
}>(), {
    type: "text",

    options: [],
    emptyOption: false,

    placeholder: "– brak –",
    required: false,
    autofocus: false,
    disabled: false,
    checked: false,
    autocomplete: "on",
})

if (props.type === "storage_url") {
    props.type = "url"
    storageFile.value = true
}

if (
    (props.type === "url" && props.value)
    || storageFile.value
) {
    extraButtons.value = true
}
</script>

<template>
    <div class="input-outer-wrapper">

        <div class="input-inner-wrapper">
            <Icon v-if="props.icon" :name="props.icon" />

            <label :for="props.name">
                {{ props.label }}
                <span v-if="props.hint">
                    <Icon icon="" />
                </span>
                :
            </label>

            <input v-if="props.type === 'checkbox'" type="checkbox"
                :id="props.name"
                :name="props.name"
                :value="props.value"

                :required="props.required"
                :autofocus="props.autofocus"
                :disabled="props.disabled"
                :checked="props.checked"
            />

            <select v-else-if="props.type === 'select'"
                :id="props.name"
                :name="props.name"

                :required="props.required"
                :autofocus="props.autofocus"
                :disabled="props.disabled"
            >
                <option v-if="props.emptyOption" value="">— brak —</option>
                <option v-for="(opt_value, opt_label) in props.options" :key="opt_value"
                    :value="opt_value"
                    :selected="opt_value == props.value"
                >
                    {{ opt_label }}
                </option>
            </select>

            <textarea v-else-if="props.type === 'TEXT'"
                :id="props.name"
                :name="props.name"
                placeholder="Zacznij pisać..."

                :required="props.required"
                :autofocus="props.autofocus"
                :disabled="props.disabled"
            >{{ props.value }}</textarea>

            <x-ckeditor v-else-if="props.type === 'HTML'"
                :name="props.name"
                :value="props.value"
            />

            <template v-else-if="props.type === 'JSON'">
                <!--
                <input type="hidden" :name="props.name" :value="props.value ? JSON.parse(props.value) : null">
                <table :data-name="props.name" :data-columns="props.columnTypes.length">
                    <thead>
                        <tr>
                            <th v-for="key in Object.keys(props.columnTypes)" :key="key">{{ key }}</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="(val, key) in props.value" :key="key">
                            @php $i = 0; @endphp
                            @switch (count($columnTypes))
                                @case (2)
                                {{-- key-value array --}}
                                @foreach ($columnTypes as $t)
                                <td class="rounded">
                                    <input type="{{ $t }}" value="{{ ($i++ == 0) ? $key : $val }}" onchange="JSONInputUpdate('{{ $name }}')" />
                                </td>
                                @endforeach
                                @break

                                @case (1)
                                {{-- simple array --}}
                                <td class="rounded">
                                    <input type="{{ current($columnTypes) }}" value="{{ $val }}" onchange="JSONInputUpdate('{{ $name }}')" />
                                </td>
                                @break

                                @default
                                {{-- array of arrays --}}
                                @foreach ($columnTypes as $t)
                                <td class="rounded">
                                    <input type="{{ $t }}" value="{{ $val[$i++] }}" onchange="JSONInputUpdate('{{ $name }}')" />
                                </td>
                                @endforeach
                            @endswitch

                            <td><x-button icon="delete" class="phantom interactive" onclick="JSONInputDeleteRow('{{ $name }}', this)" /></td>
                        </tr>
                    </tbody>

                    <tfoot>
                        <tr role="new-row">
                            @foreach ($columnTypes as $t)
                            <td class="rounded">
                                <input type="{{ $t }}" onchange="JSONInputUpdate('{{ $name }}')"
                                    onkeydown="JSONInputWatchForConfirm('{{ $name }}', event);"
                                    onblur="JSONInputAddRow('{{ $name }}', )"
                                    @if ($autofillFrom)
                                    onkeyup="JSONInputAutofill('{{ $name }}', event);"
                                    @endif
                                />
                                @if ($autofillFrom)
                                <span role="autofill-hint" class="ghost flex right"></span>
                                @endif
                            </td>
                            @endforeach

                            <td>
                                <x-button icon="plus" class="accent background secondary interactive" onclick="JSONInputAddRow('{{ $name }}')" />
                                <x-button icon="delete" class="phantom interactive hidden" onclick="JSONInputDeleteRow('{{ $name }}', this)" />
                            </td>
                        </tr>
                    </tfoot>
                </table>
                -->
            </template>

            <input v-else :type="props.type"
                :id="props.name"
                :name="props.name"
                :value="props.value"
                :placeholder="props.placeholder"

                :required="props.required"
                :autofocus="props.autofocus"
                :disabled="props.disabled"
                :autocomplete="props.autocomplete"
            />
        </div>

        <div v-if="extraButtons" class="flex right middle">
            <Button v-if="storageFile" icon="folder-open" class="phantom interactive" onclick="browseFiles('{{ route('files-list', ['select' => $name]) }}')" />

            <Button v-if="props.value" :action="props.value" icon="open-in-new" class="accent background secondary" target="_blank" />
        </div>

        <!-- @if ($characterLimit)
        <span class="ghost" role="character-count"></span>
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
        @endif -->

    </div>

    <!-- @if ($autofillFrom)
    <script>
    window.autofill = window.autofill ?? {}
    fetch("{{ $autofillRoute }}").then(res => res.json()).then(data => {
        window.autofill['{{ $name }}'] = data
    })
    </script>
    @endif -->

</template>
