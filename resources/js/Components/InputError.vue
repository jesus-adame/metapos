<script setup lang="ts">
defineProps<{
    message?: string | Array<any>;
}>();
</script>

<template>
    <div v-show="message">
        <p v-if="message instanceof String" class="text-sm text-red-600">
            {{ message }}
        </p>
        <p v-if="message instanceof Array" class="text-sm text-red-600">
            {{ message[0] }}
        </p>
    </div>
</template>
