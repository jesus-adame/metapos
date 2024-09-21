<script lang="ts" setup>
import axios from 'axios';
import { onMounted, onUnmounted, ref } from 'vue';
import qz from 'qz-tray';
import Button from 'primevue/button';
import { getPrinter } from '@/helpers';

const config = ref()
const props = defineProps<{
    pdfUrl: string,
}>()

const print = () => {
    axios.get(props.pdfUrl, {
        responseType: 'arraybuffer'  // Para obtener el archivo binario
    }).then(response => {
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
        qz.print(config.value, data).then(() => {
            console.log("Impresión exitosa");
        }).catch((err: any) => console.error("Error de impresión:", err));
    }).catch(error => {
        console.error("Error al obtener el PDF:", error);
    });
}

onMounted(() => {
    qz.websocket.connect().then(() => {
        console.log("Conectado a QZ Tray");
        config.value = qz.configs.create(getPrinter() ?? '');
    }).catch((err: any) => console.error(err));
})

onUnmounted(() => {
    qz.websocket.disconnect().then(() => {
        console.log("Desconectado de QZ Tray");
    }).catch(err => console.error(err));
})
</script>
<template>
    <Button raised icon="pi pi-print" @click="print"></Button>
</template>