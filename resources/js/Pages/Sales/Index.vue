<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { formatDateTime, formatMoneyNumber, saleStatus } from '@/helpers';
import UserLayout from '@/Layouts/UserLayout.vue';
import Column from 'primevue/column';
import Button from 'primevue/button';
import CashRegisterIcon from '@/Components/icons/CashRegisterIcon.vue';
import UserIcon from '@/Components/icons/UserIcon.vue';
import { onMounted, ref } from 'vue';
import { Sale } from '@/types';
import SaleService from "@/Services/SaleService";
import { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';

const items = ref<Sale[]>([])
const totalRecords = ref<number>(0)
const saleService = new SaleService()
const page = ref<number>(1)
const rows = ref<number>(10)

const printTicket = async (saleId: number) => {
    const url = route('sales.ticket', saleId);
    window.open(url, '_blank', 'popup')
};

const fetchItems = (pageNumber: number) => {
    saleService.paginate(pageNumber, rows.value)
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
    <Head title="Ventas" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ventas</h2>
        </template>

        <div class="mt-6 mb-4">
            <Link :href="route('sales.create')">
                <Button label="Nueva venta" severity="success" icon="pi pi-plus"></Button>
            </Link>
        </div>
        <DataTable :value="items" paginator :rows="rows" :total-records="totalRecords" @page="onPage" lazy>
            <Column field="id" header="#">
                <template #body="slot">
                    <Link :href="route('sales.show', { sale: slot.data.id })">
                        {{ slot.data.id }}
                    </Link>
                </template>
            </Column>
            <Column header="Fecha">
                <template #body="slot">
                    {{ formatDateTime(slot.data.created_at) }}
                </template>
            </Column>
            <Column header="Cliente">
                <template #body="slot">
                    <UserIcon>
                        {{ slot.data.customer?.first_name || 'Sin asignar' }} {{ slot.data.customer?.last_name }}
                    </UserIcon>
                </template>
            </Column>
            <Column header="Vendedor">
                <template #body="slot">
                    <UserIcon>
                        {{ slot.data.seller.name }} {{ slot.data.seller.lastname }}
                    </UserIcon>
                </template>
            </Column>
            <Column header="Caja">
                <template #body="slot">
                    <CashRegisterIcon>
                        {{ slot.data.cash_register?.name || 'Sin asignar' }}
                    </CashRegisterIcon>
                </template>
            </Column>
            <Column field="total" header="Total">
                <template #body="slot">
                    {{ formatMoneyNumber(slot.data.total) }}
                </template>
            </Column>
            <Column header="Estatus">
                <template #body="slot">
                    {{ saleStatus(slot.data.status) }}
                </template>
            </Column>
            <Column header="">
                <template #body="slot">
                    <div class="flex">
                        <Link :href="route('sales.show', {sale: slot.data.id})">
                            <Button icon="pi pi-eye" class="mr-1" severity="info"></Button>
                        </Link>
                        <Button icon="pi pi-print" @click="printTicket(slot.data.id)"></Button>
                    </div>
                </template>
            </Column>
        </DataTable>
    </UserLayout>
</template>