<script setup lang="ts">
import { CIcon } from '@/../../node_modules/@coreui/icons-vue';
import * as icons from '@/../../node_modules/@coreui/icons';
import { computed } from 'vue';

const props = defineProps<{
    icon: string,
}>()
const icon_name = computed(() => `cil${props.icon.charAt(0).toUpperCase() + props.icon.slice(1)}`);
</script>

<template>
    <CIcon :icon="icons[icon_name]" />
</template>
