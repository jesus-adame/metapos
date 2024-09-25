<script lang="ts" setup>
import axios from 'axios';
import { onMounted, onUnmounted } from 'vue';
import qz from 'qz-tray';
import Button from 'primevue/button';
import { connectQZ, disconnectQZ } from '@/helpers';
import { useToast } from 'primevue/usetoast';

const toast = useToast()
const props = defineProps<{
    pdfUrl: string,
    printer: string
}>()

const print = () => {
    axios.get(props.pdfUrl, {
        responseType: 'arraybuffer'  // Para obtener el archivo binario
    }).then(response => {
        const config = qz.configs.create(props.printer);
        const base64PDF = btoa(
            new Uint8Array(response.data).reduce((data, byte) => data + String.fromCharCode(byte), '')
        );

        // Definir los datos para QZ Tray
        const data: any[] = [{
            type: 'pdf',
            format: 'base64',
            data: base64PDF
        }];

        // Enviar a imprimir
        qz.print(config, data).then(() => {
            toast.add({ severity: 'success', summary: 'Correcto', detail: 'Imprimiendo documento', life: 1100 });
        }).catch((err: any) => console.error("Error de impresión:", err));
    }).catch(error => {
        console.error("Error al obtener el PDF:", error);
    });
}

onMounted(() => {
    connectQZ()
})

onUnmounted(() => {
    disconnectQZ()
})
</script>
<template>
    <Button raised icon="pi pi-print" @click="print"></Button>
</template>