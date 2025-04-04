<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    action: string | Function,
    icon?: string,
}>()
</script>

<template>
    <div class="button">
        <Link v-if="typeof props.action === 'string'" :href="props.action">
            <slot />
        </Link>

        <template v-else>
            <slot />
        </template>
    </div>
</template>
