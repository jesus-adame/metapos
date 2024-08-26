<script lang="ts" setup>
import { formatDateTime, formatMoneyNumber, purchaseSeverity, purchaseStatus } from '@/helpers';
import Button from 'primevue/button';
import Column from 'primevue/column';
import Tag from 'primevue/tag';
import UserIcon from '@/Components/icons/UserIcon.vue';
import { AxiosResponse } from 'axios';
import PurchaseService from "@/Services/PurchaseService";
import { onMounted, ref } from 'vue';
import { Purchase } from '@/types';
import { DataTablePageEvent } from 'primevue/datatable';
import { Link } from '@inertiajs/vue3';

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
    <DataTable :value="items" @page="onPage" :total-records="totalRecords" :rows="rows" lazy paginator>
        <Column field="id" header="#"></Column>
        <Column header="Fecha y hora">
            <template #body="slot">
                {{ formatDateTime(slot.data.applicated_at) }}
            </template>
        </Column>
        <Column header="Estatus">
            <template #body="{data}">
                <Tag :severity="purchaseSeverity(data.status)" :value="purchaseStatus(data.status)"></Tag>
            </template>
        </Column>
        <Column header="Proveedor">
            <template #body="slot">
                <UserIcon>
                    {{ slot.data.supplier?.name || '-' }} {{ slot.data.supplier?.lastname }}
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
        <Column field="total" header="Total">
            <template #body="{data}">
                <span class="font-bold">
                    {{ formatMoneyNumber(data.total) }}
                </span>
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
</template>