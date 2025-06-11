<script setup lang="ts">
import BaseLayout from "@/Layouts/Shipyard/BaseLayout.vue";
import Input from "../../Components/Shipyard/Inputs/Input.vue";
import Button from "../../Components/Shipyard/Inputs/Button.vue";
import { useForm } from "@inertiajs/vue3";

defineOptions({ layout: BaseLayout })

const form = useForm({
    name: null,
    password: null,
    remember: false,
})

</script>

<template>
<form @submit.prevent="form.post(route('process-login'))" class="flex down center middle">
    <Logo />
    <Input
        v-model="form.name"
        name="name"
        label="Login"
        icon="user"
        required
    />
    <Input type="password"
        v-model="form.password"
        name="password"
        label="Hasło"
        icon="key"
        required
    />
    <Input type="checkbox"
        v-model="form.remember"
        name="remember"
        label="Zapamiętaj mnie"
        icon="bookmark"
    />

    <div class="bottom-btns">
        <Button action="submit" icon="sign-in">Zaloguj się</Button>
    </div>
</form>
</template>
