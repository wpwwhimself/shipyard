<script setup lang="ts">
import ApplicationLogo from '@/Components/Shipyard/ApplicationLogo.vue';
import Header from '@/Components/Shipyard/BigBlocks/Header.vue';
import Footer from '@/Components/Shipyard/BigBlocks/Footer.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    title: string,
}>();
</script>

<template>
    <Head :title="title" />
    <Header :title="title" />
    <main class="padded">
        <slot />
    </main>
    <Footer />
</template>

<style scoped>
main {
    flex-grow: 1;
    width: 1280px;
    max-width: calc(100vw - 8em);
    margin: 0 auto;
}
</style>
