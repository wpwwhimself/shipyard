<script setup lang="ts">
import H from './H.vue';
import Icon from './Icon.vue';

const props = defineProps<{
    title?: string,
    icon?: string,
}>()
</script>

<template>
    <div class="card">
        <div class="title-wrapper" v-if="props.title">
            <h :icon="props.icon">{{ props.title }}</h>

            <div class="buttons" v-if="$slots.buttons">
                <slot name="buttons" />
            </div>
        </div>

        <div class="contents">
            <slot />
        </div>
    </div>
</template>
