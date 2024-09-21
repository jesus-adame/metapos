<script lang="ts" setup>
import { onMounted, onUnmounted, ref } from 'vue';
import qz from 'qz-tray';
import Button from 'primevue/button';
import { getPrinter } from '@/helpers';

const selectedPrinter = ref('')
const printers = ref([])

const selectPrinter = () => {
    if (selectedPrinter.value) {
        console.log(`Impresora seleccionada: ${selectedPrinter.value}`);
        saveSelectedPrinter();
    }
}

const saveSelectedPrinter = () => {
    localStorage.setItem('selectedPrinter', selectedPrinter.value);
}

const findPrinters = () => {
    if (qz.websocket.isActive()) {
        qz.printers.find().then((finded_printers: any) => {
            console.log(finded_printers);  // Lista de impresoras disponibles
            // Puedes seleccionar una impresora por su nombre

            printers.value = finded_printers
        }).catch((err: any) => console.error(err));
    }
}

const connectQZ = () => {
    if (!qz.websocket.isActive()) {
        qz.websocket.connect().then(() => {
            console.log("Conectado a QZ Tray");
        }).catch((err: any) => console.error(err));
    }
}

const disconnectQZ = () => {
    if (qz.websocket.isActive()) {
        qz.websocket.disconnect().then(() => {
           console.log("Desconectado de QZ Tray");
        }).catch(err => console.error(err));
    }
}

onMounted(() => {
    connectQZ()
    selectedPrinter.value = getPrinter() ?? '';
})

onUnmounted(() => {
    disconnectQZ()
})
</script>
<template>
    <div class="py-4 px-6 bg-white">
        <h2 class="text-lg font-bold mb-4">Impresoras</h2>
        <p class="pb-2">Impresora de tickets</p>
        <div class="flex gap-2 mb-4">
            <span class="p-2 flex gap-2 items-center border bg-gray-100">
                <i class="pi pi-print"></i>
                {{ selectedPrinter }}
            </span>
            <span class="flex items-center">Connected: {{ qz.websocket.isActive() }}</span>
            <Button icon="pi pi-undo" rounded raised severity="success" @click="connectQZ"></Button>
        </div>
        <div class="mb-4 flex items-center gap-2">
            <span>Cambiar impresora</span>
        </div>
        <div class="flex gap-2">
            <select v-model="selectedPrinter" @change="selectPrinter">
                <option value="">Seleccione una impresora</option>
                <option v-for="printer in printers" :key="printer" :value="printer">
                    {{ printer }}
                </option>
            </select>
            <Button raised rounded label="Buscar impresoras" severity="info" @click="findPrinters"></Button>
        </div>
  </div>
</template>