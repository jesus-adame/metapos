<script lang="ts" setup>
import { locationIcon } from '@/helpers';
import { Location } from "@/types"

const props = defineProps<{
    location: Location
}>()
</script>
<template>
    <div class="flex items-center">
        <div class="py-1 px-2 mr-2 bg-gray-200 text-gray-500 rounded-full">
            <i :class="locationIcon(props.location)"></i>
        </div>
        <slot></slot>
    </div>
</template>