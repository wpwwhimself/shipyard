<script setup lang="ts">
import '@/../../node_modules/primeicons/primeicons.css';

const props = defineProps<{
    name: string,
}>()
</script>

<template>
    <i :class="`pi pi-${props.name}`"></i>
</template>
