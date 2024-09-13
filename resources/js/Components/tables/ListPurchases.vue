<script lang="ts" setup>
import { formatDateTime, formatMoneyNumber, locationIcon, purchaseSeverity, purchaseStatus } from '@/helpers';
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
            <template #body="{data}">
                {{ formatDateTime(data.applicated_at) }}
            </template>
        </Column>
        <Column header="Estatus">
            <template #body="{data}">
                <Tag :severity="purchaseSeverity(data.status)" :value="purchaseStatus(data.status)"></Tag>
            </template>
        </Column>
        <Column header="Proveedor">
            <template #body="{data}">
                <UserIcon>
                    {{ data.supplier?.name || '-' }} {{ data.supplier?.lastname }}
                </UserIcon>
            </template>
        </Column>
        <Column header="Comprador">
            <template #body="{data}">
                <UserIcon>
                    <span>
                        {{ data.buyer?.name }} {{ data.buyer?.lastname }}
                    </span>
                </UserIcon>
            </template>
        </Column>
        <Column field="location" header="Ubicación">
            <template #body="{data}">
                <div class="flex items-center">
                    <div class="py-2 px-3 bg-gray-200 rounded-full mr-3 text-gray-500">
                        <i :class="locationIcon(data.location)"></i>
                    </div>
                    <div>
                        <span>{{ data.location.name }}</span>
                    </div>
                </div>
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
            <template #body="{data}">
                <Link :href="route('purchases.show', {purchase: data.id})">
                    <Button icon="pi pi-eye" severity="info"></Button>
                </Link>
            </template>
        </Column>
    </DataTable>
</template>