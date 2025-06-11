<script setup lang="ts">
import '@/../../node_modules/primeicons/primeicons.css';

const props = defineProps<{
    name: string,
}>()
</script>

<template>
    <img v-if="props.name.endsWith('.svg')"
        :src="name"
        :alt="name"
        class="icon"
    >
    <i :class="`pi pi-${props.name}`"></i>
</template>
