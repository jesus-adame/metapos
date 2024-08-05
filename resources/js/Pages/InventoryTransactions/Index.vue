<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import DataTable, { DataTablePageEvent } from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Card from '@/Components/Card.vue';
import { onMounted, ref } from 'vue';
import CreateInventoryTransaction from '@/Components/forms/CreateInventoryTransaction.vue';
import InventoryTransactionService from '@/Services/InventoryTransactionService';
import { InventoryTransaction } from '@/types';
import { AxiosResponse } from 'axios';
import ListInventoryTransactions from '@/Components/tables/ListInventoryTransactions.vue';

const modalCreateMovement = ref(false);
const page = ref(1);
const items = ref([]);
const totalRecords = ref(0)
const inventoryTransactionService = new InventoryTransactionService();


const showModalCreateMovement = () => {
    modalCreateMovement.value = true
}

const hideModalCreateMovement = () => {
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
</script>
<template>
    <Head title="Movimientos de Inventario" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Movimientos de Inventario</h2>
        </template>

        <div class="mb-4 mt-6">
            <Link :href="route('products.index')">
                <Button label="Productos" icon="pi pi-box"></Button>
            </Link>
        </div>

        <ListInventoryTransactions></ListInventoryTransactions>
    </UserLayout>
</template>
