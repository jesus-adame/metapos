<script setup lang="ts">
import { can, formatDateTime, formatMoneyNumber, saleStatus } from '@/helpers';
import Column from 'primevue/column';
import CashRegisterIcon from '@/Components/icons/CashRegisterIcon.vue';
import UserIcon from '@/Components/icons/UserIcon.vue';
import { DataTablePageEvent } from 'primevue/datatable';
import { onMounted, ref } from 'vue';
import { Sale } from '@/types';
import SaleService from "@/Services/SaleService";
import { AxiosResponse } from 'axios';
import { Link } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';

const items = ref<Sale[]>([])
const totalRecords = ref<number>(0)
const saleService = new SaleService()
const page = ref<number>(1)
const rows = ref<number>(10)
const cashRegister = ref<number | null>(null)
const modalTicket = ref<boolean>(false)
const saleId = ref<number | null>(null)

const fetchItems = (pageNumber: number) => {
    saleService.paginate(pageNumber, rows.value, cashRegister.value)
    .then((response: AxiosResponse) => {
        const pagination = response.data
        items.value = pagination.data
        totalRecords.value = pagination.total
    })
}

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchItems(pageNumber);
}

const openModaTicket = (id: number) => {
    saleId.value = id
    modalTicket.value = true
}

onMounted(() => {
    fetchItems(page.value)
})
</script>
<template>
    <Dialog v-model:visible="modalTicket" modal :header="'Venta #' + saleId">
        <PdfObject :url="route('sales.ticket', {id: saleId})" :options="{ height: '100vh', width: '30vw', border: '1px', solid: '#ccc' }" />
    </Dialog>

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
            <template #body="{data}">
                <div class="flex">
                    <Link :href="route('sales.show', {sale: data.id})">
                        <Button icon="pi pi-eye" class="mr-1" severity="info"></Button>
                    </Link>
                    <Button icon="pi pi-print" @click="openModaTicket(data.id)"></Button>
                </div>
            </template>
        </Column>
    </DataTable>
</template>