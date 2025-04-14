<script setup lang="ts">
import Icon from './Icon.vue';

const props = defineProps<{
    title?: string,
    icon?: string,
}>()
</script>

<template>
    <div class="card">
        <div class="title-wrapper" v-if="props.title">
            <h2>
                <Icon v-if="props.icon" :icon="props.icon" />
                {{ props.title }}
            </h2>

            <div class="buttons" v-if="$slots.buttons">
                <slot name="buttons" />
            </div>
        </div>

        <slot />
    </div>
</template>
