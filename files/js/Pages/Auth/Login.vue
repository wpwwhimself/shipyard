<script setup lang="ts">
import BaseLayout from "@/Layouts/Shipyard/BaseLayout.vue";
import Card from "../../Components/Card.vue";
import Input from "../../Components/Inputs/Input.vue";

defineOptions({ layout: BaseLayout })
</script>

<template>
    <Card title="Administracja" icon="sign-in">
        <Input
            name="name"
            label="Login"
            icon="user"
            required
        />
        <Input type="password"
            name="password"
            label="Hasło"
            icon="key"
            required
        />
    </Card>
</template>
