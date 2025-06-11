<script lang="tsx" setup>
import BaseLayout from '@/Layouts/Shipyard/BaseLayout.vue';
import H from '@/Components/Shipyard/H.vue';
import Card from '@/Components/Shipyard/Card.vue';
import Button from '@/Components/Shipyard/Inputs/Button.vue';
import { useForm } from '@inertiajs/vue3';
import Input from '@/Components/Shipyard/Inputs/Input.vue';

const props = defineProps<{
    data: any,
    meta: any,
    scope: any,
    fields: any,
    connections: any,
    actions: any,
}>();

const pFields = Object.values(props.fields).map((fld: any, i: number) => ({ ...fld, name: Object.keys(props.fields)[i] }));
const pConnections = Object.values(props.connections).map((fld: any, i: number) => ({ ...fld, name: Object.keys(props.connections)[i] }));

const formFields: any = {
    method: "save",
};
pFields.forEach(fld => formFields[fld.name] = (props.data)
    ? props.data[fld.name]
    : (fld.default ?? null)
);
const form = useForm(formFields);

defineOptions({ layout: BaseLayout });
</script>

<template>
    <H v-if="data" lvl="1" :icon="data.logo_url ?? meta.icon">{{ data.name }} – edycja</H>
    <H v-else lvl="1" :icon="meta.icon">{{ meta.label }} – tworzenie</H>


    <form @submit.prevent="form.post(route('admin-process-edit-model', { model: meta.name }))">
        <Card
            title="Podstawowe informacje"
            icon="pencil"
        >
            <Input v-for="field in pFields" :key="field.name"
                :type="field.type"
                v-model="form[field.name]"
                :name="field.name"
                :label="field.label"
                :icon="field.icon"
                :required="field.required"
            />
        </Card>

        <Card v-if="connections"
            title="Połączenia"
            icon="link"
        >
            <template v-for="connection in pConnections" :key="connection.name">
                <Input v-if="connection.mode === 'one'"
                    type="select"
                    :options="connection.options"
                    v-model="form[connection.name]"
                    :name="connection.name"
                    :label="connection.meta.name"
                    :icon="connection.icon"
                    :required="connection.required"
                />
            </template>
        </Card>

        <div class="bottom-btns">
            <Button :action="route('admin-list-model', { model: meta.name })" icon="arrow-left">Powrót</Button>
            <Button action="submit" icon="save">Zapisz</Button>
        </div>
    </form>
</template>

<style lang="scss" scoped>
</style>
