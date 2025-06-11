<script setup lang="ts">
import Button from '../Inputs/Button.vue';
import { usePage } from '@inertiajs/vue3';

interface Navdata {
    name: string,
    label: string,
    icon: string,
    description: string,
}
interface Route {
    action: string,
    label: string,
    icon: string,
}

const page = usePage();
const routes = page.props.nav
    .map((navdata: Navdata) => ({
        label: navdata.label,
        icon: navdata.icon,
        action: route('admin-list-model', { model: navdata.name }),
    }));
</script>

<template>
    <nav>
        <Button v-for="route in routes"
            :action="route.action"
            :icon="route.icon"
        >
            {{ route.label }}
        </Button>
    </nav>
</template>
