<script lang="ts" setup>
import { InventoryTransaction, Product } from '@/types';
import Column from 'primevue/column';
import DataTable, { DataTablePageEvent } from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Tag from 'primevue/tag';
import { onMounted, ref } from 'vue';
import axios, { AxiosResponse } from 'axios';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import InventoryTransactionService from '@/Services/InventoryTransactionService';
import CreateInventoryTransaction from '../forms/CreateInventoryTransaction.vue';
import Button from 'primevue/button';
import { can } from '@/helpers';

const modalCreateMovement = ref<Boolean>(false)
const items = ref<Product[]>([])
const inventoryTransactionService = new InventoryTransactionService();
const toast = useToast()
const confirm = useConfirm()
const rows = ref<number>(10)
const page = ref(1);
const totalRecords = ref(0)

const openModalCreate = () => {
    modalCreateMovement.value = true
}

const closeModalCreate = () => {
    modalCreateMovement.value = false
    fetchItems(page.value)
}

const fetchItems = (pageNumber: number) => {
    inventoryTransactionService.paginate(pageNumber)
    .then((response: AxiosResponse) => {
        const paginate = response.data.paginate

        items.value = paginate.data
        totalRecords.value = paginate.total
    })
}

onMounted(() => {
    fetchItems(page.value)
})

const deleteItem = (url: string) => {
    axios.post(url, { _method: 'delete' })
    .then((response: AxiosResponse) => {
        console.log(response);
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 1500 })
        fetchItems(page.value)
    })
    .catch(reject => {
        console.error(reject.response.data.errors);
        toast.add({ summary: 'Error', detail: reject.response.data.message, severity: 'error', life: 3000 })
    })
}

const confirmDelete = (url: string) => {
    confirm.require({
        header: '¿Está seguro?',
        message: 'Se eliminará el producto con su inventario',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancelar',
            severity: 'secondary',
        },
        acceptProps: {
            label: 'Eliminar',
            severity: 'danger'
        },
        accept: () => {
            deleteItem(url)
        }
    });
}

const calculateSeverity = (transaccion: InventoryTransaction) => {
    switch (transaccion.type) {
        case 'entry':
            return 'success';
        case 'exit':
            return 'danger';
    }
}

const calculateIcon = (transaccion: InventoryTransaction) => {
    switch (transaccion.type) {
        case 'entry':
            return 'pi pi-angle-up';
        case 'exit':
            return 'pi pi-angle-down';
    }
}

const calculateLabel = (transaccion: InventoryTransaction) => {
    switch (transaccion.type) {
        case 'entry':
            return 'Entrada';
        case 'exit':
            return 'Salida';
    }
}

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchItems(pageNumber);
}
</script>
<template>
    <Dialog v-model:visible="modalCreateMovement" modal header="Nuevo movimiento" :style="{ width: '35rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <CreateInventoryTransaction @save="closeModalCreate"></CreateInventoryTransaction>
    </Dialog>

    <DataTable :value="items" paginator :rows="rows" @page="onPage" :totalRecords="totalRecords">
        <Column field="applicated_at" header="Fecha"></Column>
        <Column header="Tipo">
            <template #body="slot">
                <Tag :value="calculateLabel(slot.data)" :severity="calculateSeverity(slot.data)" :icon="calculateIcon(slot.data)"></Tag>
            </template>
        </Column>
        <Column header="Producto">
            <template #body="slot">
                {{ slot.data.product.name }}
            </template>
        </Column>
        <Column field="quantity" header="Cantidad"></Column>
        <Column field="description" header="Descripción"></Column>
        <Column field="" header="">
            <template #header>
                <Button v-if="can('create products')" icon="pi pi-plus" severity="success" rounded raised @click="openModalCreate"></Button>
            </template>
        </Column>
    </DataTable>
</template>