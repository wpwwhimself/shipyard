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
    <header>
        <div class="title-wrapper">
            <Logo />
            <h1 v-if="title">{{ title }}</h1>
        </div>

        <div class="nav-wrapper">
            <Nav />
            <AuthButtons />
        </div>
    </header>
</template>
