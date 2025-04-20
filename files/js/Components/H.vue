<script setup lang="ts">
import Icon from './Icon.vue';

const props = withDefaults(defineProps<{
    lvl: string,
    icon?: string,
}>(), {
    lvl: 2,
})
</script>

<template>
    <h1 v-if="props.lvl == 1">
        <Icon v-if="props.icon" :name="props.icon" />
        <slot />
    </h1>

    <h2 v-else-if="props.lvl == 2">
        <Icon v-if="props.icon" :name="props.icon" />
        <slot />
    </h2>

    <h3 v-else-if="props.lvl == 3">
        <Icon v-if="props.icon" :name="props.icon" />
        <slot />
    </h3>

    <h4 v-else-if="props.lvl == 4">
        <Icon v-if="props.icon" :name="props.icon" />
        <slot />
    </h4>
</template>

<style scoped>
h1, h2, h3, h4 {
    & > i {
        margin-right: 0.5em;
    }
}
</style>
