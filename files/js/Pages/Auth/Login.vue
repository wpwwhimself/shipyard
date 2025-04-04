<script setup lang="ts">
import BaseLayout from '@/Layouts/BaseLayout.vue';
import Card from '@/Components/Basic/Card.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <BaseLayout title="Zaloguj się">
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <Card>
            <form @submit.prevent="submit">
            </form>
        </Card>
    </BaseLayout>
</template>
