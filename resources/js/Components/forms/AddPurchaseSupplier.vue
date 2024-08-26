<script lang="ts" setup>
import SupplierService from '@/Services/SupplierService';
import { Supplier, Purchase } from '@/types';
import { AutoCompleteCompleteEvent } from 'primevue/autocomplete';
import { reactive, ref } from 'vue';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import axios, { AxiosResponse } from 'axios';
import CreateSupplier from './CreateSupplier.vue';
import { useToast } from 'primevue/usetoast';

const props = defineProps<{
    purchase: Purchase
}>()

const emit = defineEmits(['save'])

const toast = useToast();
const supplierService = new SupplierService();
const filteredSuppliers = ref<Supplier[]>([]);
const selectedSupplier = ref<Supplier | null>(null);
const modalCreateSupplier = ref(false)
const form = reactive<{
    supplier: any,
    supplier_id: number | null
}>({
    supplier: null,
    supplier_id: null
})

const addSupplier = () => {
    axios.post(route('api.purchases.set-supplier', {purchase: props.purchase?.id}), form)
    .then((response: AxiosResponse) => {
        toast.add({ severity: 'success', summary: 'Correcto', detail: response.data.message, life: 2000 })
        emit('save')
        clearSupplier()
    })
}

const setSupplier = () => {
    form.supplier_id = selectedSupplier.value?.id || null
}

const searchSupplier = (event: AutoCompleteCompleteEvent) => {
    setTimeout(() => {
        supplierService.findByCode(event.query.trim())
            .then((response) => {
                filteredSuppliers.value = [...response.data]; // Asegúrate de que response.data es un array
            })
            .catch((error) => {
                console.error('Error fetching suppliers', error);
            });
    }, 250);
}

const showModalCreateSupplier = () => {
    modalCreateSupplier.value = true
}

const hideModalCreateSupplier = () => {
    modalCreateSupplier.value = false
}

const clearSupplier = () => {
    selectedSupplier.value = null
}
</script>
<template>
    <Dialog v-model:visible="modalCreateSupplier" modal header="Registrar cliente" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <CreateSupplier class="mt-4" @save="hideModalCreateSupplier"></CreateSupplier>
    </Dialog>

    <div class="flex pt-2">
        <AutoComplete v-model="selectedSupplier" optionLabel="name" :suggestions="filteredSuppliers"
            @complete="searchSupplier"
            @change="setSupplier"
            class="w-full"
            inputClass="w-full"
            placeholder="Buscar proveedor">
            <template #option="slot">
                <div class="flex align-options-center">
                    <div>
                        {{ slot.option.name }} {{ slot.option.lastname }} | {{ slot.option.phone }}
                    </div>
                </div>
            </template>
        </AutoComplete>
        <Button v-tooltip.top="'Nuevo proveedor'" icon="pi pi-plus" class="ml-2" severity="success" raised @click="showModalCreateSupplier"></Button>
    </div>
    <div v-if="selectedSupplier?.id" class="flex justify-between items-center my-2">
        <Tag :value="selectedSupplier?.name + ' ' + selectedSupplier?.lastname + ' ' + selectedSupplier?.phone"></Tag>
        <Button severity="danger" icon="pi pi-times" text @click="clearSupplier"></Button>
    </div>
    <form @submit.prevent="addSupplier()">
        <div class="flex justify-end mt-4">
            <Button v-if="selectedSupplier?.id" type="submit" label="Aplicar" severity="success"></Button>
        </div>
    </form>
</template>