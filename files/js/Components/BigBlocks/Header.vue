<script setup lang="ts">
import { ref } from 'vue';
import Card from '../Card.vue';
import AuthButtons from './AuthButtons.vue';
import Logo from './Logo.vue';
import Nav from './Nav.vue';

const title = ref(window.document.title.split(' | ')[0]);

// watch(() => window.document.title, (newTitle) => {
//     title.value = newTitle.split(' | ')[0];
// });
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
