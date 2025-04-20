<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Button from '../Inputs/Button.vue';
</script>

<template>
    <div id="auth-btns">
        <Button :action="route('login')" icon="sign-in">Zaloguj się</Button>
    </div>
</template>
