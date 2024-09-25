<script lang="ts" setup>
import { onMounted, onUnmounted, ref } from 'vue';
import qz from 'qz-tray';
import Button from 'primevue/button';
import { connectQZ, disconnectQZ, getLabelPrinter, getPrinter, signingQZ } from '@/helpers';

const selectedPrinter = ref('')
const selectedLabelPrinter = ref('')
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

const saveSelectedLabelPrinter = () => {
    localStorage.setItem('selectedLabelPrinter', selectedLabelPrinter.value);
}

const findPrinters = () => {
    if (qz.websocket.isActive()) {
        qz.printers.find().then((finded_printers: any) => {
            // Puedes seleccionar una impresora por su nombre
            printers.value = finded_printers
        }).catch((err: any) => console.error(err));
    }
}

onMounted(() => {
    connectQZ()
    selectedPrinter.value = getPrinter() ?? '';
    selectedLabelPrinter.value = getLabelPrinter() ?? '';
})

onUnmounted(() => {
    disconnectQZ()
})
</script>
<template>
    <div class="py-4 px-6 bg-white grid gap-2">
        <div class="flex items-center mb-4 gap-4">
            <h2 class="text-xl font-bold">Impresoras</h2>
            <Button raised rounded label="Buscar impresoras" severity="info" @click="findPrinters"></Button>
        </div>

        <p>Impresora de tickets</p>
        <div class="flex gap-2 mb-4">
            <span class="p-2 flex gap-2 items-center border bg-gray-100">
                <i class="pi pi-print"></i>
                {{ selectedPrinter }}
            </span>
            <span class="flex items-center">Connected: {{ qz.websocket.isActive() }}</span>
            <Button icon="pi pi-undo" rounded raised severity="success" @click="connectQZ"></Button>
        </div>
        <div class="flex items-center gap-2">
            <span>Cambiar impresora</span>
        </div>
        <div class="flex gap-2 mb-4">
            <select v-model="selectedPrinter" @change="selectPrinter">
                <option value="">Seleccione una impresora</option>
                <option v-for="printer in printers" :key="printer" :value="printer">
                    {{ printer }}
                </option>
            </select>
        </div>

        <p>Impresora de etiquetas</p>
        <div class="flex gap-2 mb-4">
            <span class="p-2 flex gap-2 items-center border bg-gray-100">
                <i class="pi pi-print"></i>
                {{ selectedLabelPrinter }}
            </span>
            <span class="flex items-center">Connected: {{ qz.websocket.isActive() }}</span>
            <Button icon="pi pi-undo" rounded raised severity="success" @click="connectQZ"></Button>
        </div>
        <div class="flex items-center gap-2">
            <span>Cambiar impresora</span>
        </div>
        <div class="flex gap-2">
            <select v-model="selectedLabelPrinter" @change="saveSelectedLabelPrinter">
                <option value="">Seleccione una impresora</option>
                <option v-for="printer in printers" :key="printer" :value="printer">
                    {{ printer }}
                </option>
            </select>
        </div>
        <div>
            <Button severity="info" as="a" label="Plugins y recursos" href="https://drive.google.com/drive/folders/1g46fzytBNDeAYPe8JVo0Rd3wFEWHoP3g?usp=drive_link" text target="_blank" rel="noopener" />
        </div>
  </div>
</template>