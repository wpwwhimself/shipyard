<script setup lang="ts">
import { watchEffect, ref } from 'vue'
import { usePage } from '@inertiajs/vue3';
import Icon from './Icon.vue';

const page = usePage()
const visible = ref(false)

const icons = {
    success: 'check',
    warning: 'exclamation',
    error: 'times',
}

function hideToast() {
    visible.value = false
}

watchEffect(() => {
    if (page.props.toast) {
        visible.value = true
        setTimeout(hideToast, 5e3)
    }
})
</script>

<template>
    <div :class="[
        'toast',
        page.props.toast?.type,
        visible && 'visible',
    ].filter(Boolean).join(' ')"
        @click="hideToast"
    >
        <template v-if="page.props.toast">
            <Icon :name="icons[page.props.toast.type]" />
            <span class="content">{{ page.props.toast.message }}</span>
        </template>
    </div>
</template>
