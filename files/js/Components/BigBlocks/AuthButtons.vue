<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import Button from '../Inputs/Button.vue';

const page = usePage();
</script>

<template>
    <div id="auth-btns">
        <Button v-if="!page.props.auth.user" :action="route('login')" icon="sign-in">Zaloguj się</Button>
        <Button v-else :action="route('logout')" icon="sign-out">Wyloguj się</Button>
    </div>
</template>
