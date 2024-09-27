<script lang="ts" setup>
import { onMounted, onUnmounted } from 'vue';
import Button from 'primevue/button';
import PrinterService from '@/Services/PrinterService';

const props = defineProps<{
    pdfUrl: string,
    printer: string
}>()

const printer = new PrinterService()

onMounted(() => {
    printer.connect()
})

onUnmounted(() => {
    printer.disconect()
})
</script>
<template>
    <Button raised icon="pi pi-print" @click="printer.print(props.pdfUrl, props.printer)" label="Ticket"></Button>
</template>