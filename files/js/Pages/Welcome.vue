<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <BaseLayout title="Stwórz nową stronę">
        <h1>Tu będzie coś fajnego</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magni incidunt, sint veniam a asperiores cumque! Cum debitis quis aliquid nulla fugiat unde facilis natus dolorum, dolore minima iusto consectetur nam.</p>

        <h2>Napisz, co tylko chcesz</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magni incidunt, sint veniam a asperiores cumque! Cum debitis quis aliquid nulla fugiat unde facilis natus dolorum, dolore minima iusto consectetur nam.</p>

        <h3>Będziesz zadowolony</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magni incidunt, sint veniam a asperiores cumque! Cum debitis quis aliquid nulla fugiat unde facilis natus dolorum, dolore minima iusto consectetur nam.</p>
    </BaseLayout>
</template>
