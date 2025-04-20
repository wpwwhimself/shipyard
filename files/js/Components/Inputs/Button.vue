<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Icon from '../Icon.vue';

const props = defineProps<{
    action: string | Function,
    icon?: string,
}>()
</script>

<template>
    <button v-if="props.action === 'submit'" class="button"
        type="submit"
    >
        <Icon v-if="props.icon" :name="props.icon" />
        <slot />
    </button>

    <Link v-else-if="typeof props.action === 'string'"
        class="button"
        :href="props.action"
    >
        <Icon v-if="props.icon" :name="props.icon" />
        <slot />
    </Link>

    <span v-else class="button">
        <Icon v-if="props.icon" :name="props.icon" />
        <slot />
    </span>
</template>

<style scoped>
.button {
    & i {
        margin-right: 0.5em;
    }
}
</style>
