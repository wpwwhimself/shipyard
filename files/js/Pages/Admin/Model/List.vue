<script lang="tsx" setup>
import BaseLayout from '@/Layouts/Shipyard/BaseLayout.vue';
import H from '@/Components/Shipyard/H.vue';
import Card from '@/Components/Shipyard/Card.vue';
import Button from '@/Components/Shipyard/Inputs/Button.vue';

defineProps<{
    data: any,
    meta: any,
    actions: any,
}>();

defineOptions({ layout: BaseLayout });
</script>

<template>
    <H lvl="1" :icon="meta.icon">{{ meta.label }}</H>
    <p>{{ meta.description }}</p>

    <div class="flex down">
        <div role="list">
            <p class="ghost" v-if="data.data.length == 0">
                Brak wpisów
            </p>
            <Card v-else
                v-for="item in data.data" :key="item.id"
                :title="item.name"
                :icon="item.logo_url ?? meta.icon"
            >
                <p v-if="item.description">{{ item.description }}</p>

                <template #buttons>
                    <Button
                        :action="route('admin-edit-model', { model: meta.name, id: item.id })"
                        icon="pencil"
                    >
                        Edytuj
                    </Button>
                </template>
            </Card>
        </div>

        <div role="actions">
            <Button
                :action="route('admin-edit-model', { model: meta.name })"
                icon="plus"
            >
                Dodaj
            </Button>
        </div>
    </div>
</template>

<style lang="scss" scoped>
</style>
