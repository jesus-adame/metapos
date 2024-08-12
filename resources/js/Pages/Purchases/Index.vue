<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import { formatDate, formatMoneyNumber, purchaseStatus } from '@/helpers';
import UserIcon from '@/Components/icons/UserIcon.vue';
import { AxiosResponse } from 'axios';
import PurchaseService from "@/Services/PurchaseService";
import { onMounted, ref } from 'vue';
import { Purchase } from '@/types';
import { DataTablePageEvent } from 'primevue/datatable';

const items = ref<Purchase[]>([])
const purchaseService = new PurchaseService()
const totalRecords = ref<number>(0)
const page = ref<number>(1)
const rows = ref<number>(10)

const fetchItems = (pageNumber: number) => {
    purchaseService.paginate(pageNumber, rows.value)
    .then((response: AxiosResponse) => {
        const pagination = response.data
        items.value = pagination.data
        totalRecords.value = pagination.total
    })
}

onMounted(() => {
    fetchItems(page.value)
})

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchItems(pageNumber);
}
</script>

<template>
    <Head title="Compras" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Compras</h2>
        </template>

        <div class="mt-6 mb-4">
            <Link :href="route('purchases.create')">
                <Button label="Nueva compra" icon="pi pi-plus" severity="success"></Button>
            </Link>
        </div>

        <div class="mt-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <DataTable :value="items" @page="onPage" :total-records="totalRecords" :rows="rows" lazy paginator>
                <Column field="id" header="#"></Column>
                <Column header="Fecha">
                    <template #body="slot">
                        {{ formatDate(slot.data.purchase_date) }}
                    </template>
                </Column>
                <Column header="Proveedor">
                    <template #body="slot">
                        <UserIcon>
                            {{ slot.data.supplier?.first_name || 'N/A' }} {{ slot.data.supplier?.last_name }}
                        </UserIcon>
                    </template>
                </Column>
                <Column header="Comprador">
                    <template #body="slot">
                        <UserIcon>
                            {{ slot.data.buyer?.name }} {{ slot.data.buyer?.lastname }}
                        </UserIcon>
                    </template>
                </Column>
                <Column header="Estatus">
                    <template #body="slot">
                        {{ purchaseStatus(slot.data.status) }}
                    </template>
                </Column>
                <Column field="total" header="Total">
                    <template #body="slot">
                        {{ formatMoneyNumber(slot.data.total) }}
                    </template>
                </Column>
                <Column header="">
                    <template #body="slot">
                        <Link :href="route('purchases.show', {purchase: slot.data.id})">
                           <Button icon="pi pi-eye" severity="info"></Button>
                        </Link>
                    </template>
                </Column>
            </DataTable>
        </div>
    </UserLayout>
</template>
