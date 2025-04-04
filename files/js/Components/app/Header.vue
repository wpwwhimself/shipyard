<script setup lang="ts">
import Card from '../basic/Card.vue';
import AuthButtons from './AuthButtons.vue';
import Logo from './Logo.vue';
import Nav from './Nav.vue';

const props = defineProps<{
    title?: string;
}>();
</script>

<template>
    <header class="padded">
        <Card class="flex right middle padded">
            <div class="flex right middle">
                <Logo />
                <h1 v-if="title">{{ title }}</h1>
            </div>

            <div class="flex right middle">
                <Nav />
                <AuthButtons />
            </div>
        </Card>
    </header>
</template>

<style scoped>
header {
    & > .card {
        justify-content: space-between;
    }

    & h1 {
        margin: 0;
        font-size: 1.3em;
    }
}
</style>
