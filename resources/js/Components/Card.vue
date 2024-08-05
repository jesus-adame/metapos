<template>
    <div class="mx-auto mx-1" :class="[widthClass]">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-gray-900" :class="[padding]">
            <slot />
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: 'full',
    },
    padding: {
        type: String,
        default: '6',
    },
    contentClasses: {
        type: String,
        default: 'py-1 bg-white',
    },
});

const padding = computed(() => {
    return {
        '0': 'p-0',
        '1': 'p-1',
        '2': 'p-2',
        '6': 'p-6',
    }[props.padding.toString()]
})

const widthClass = computed(() => {
    return {
        '1/4': 'w-1/4',
        '2/4': 'w-2/4',
        '3/4': 'w-3/4',
        '1/3': 'w-1/3',
        '2/3': 'w-2/3',
        '1/2': 'w-1/2',
        'full': 'w-full',
    }[props.width.toString()];
});
</script>