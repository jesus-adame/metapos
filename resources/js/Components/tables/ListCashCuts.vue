<script lang="ts" setup>
import { AxiosResponse } from 'axios';
import Column from 'primevue/column';
import DataTable, { DataTablePageEvent } from 'primevue/datatable';
import { onMounted, ref } from 'vue';
import CashCutService from "@/Services/CashCutService";
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import CreateCashCut from '../forms/CreateCashCut.vue';
import { formatDate, formatMoneyNumber } from '@/helpers';

const page = ref<number>(1)
const rows = ref<number>(5)
const items = ref([])
const cashCutService = new CashCutService()
const modalCreate = ref(false)
const totalRecords = ref(0)

const fetchItems = (pageNumber: number) => {
    cashCutService.paginate(pageNumber, rows.value)
    .then((response: AxiosResponse) => {
        items.value = response.data.data
        totalRecords.value = response.data.total
    })
}

const showModalCreate = () => {
    modalCreate.value = true
}

const hideModalCreate = () => {
    modalCreate.value = false
    fetchItems(page.value);
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
    <Dialog v-model:visible="modalCreate" header="Nuevo corte" modal :style="{ width: '35rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <CreateCashCut @save="hideModalCreate"></CreateCashCut>
    </Dialog>

    <DataTable :value="items" paginator :rows="rows" :totalRecords="totalRecords" @page="onPage" lazy>
        <Column field="cut_date" header="Fecha de corte">
            <template #body="slot">
                {{ formatDate(slot.data.cut_date, true) }} - {{ formatDate(slot.data.cut_end_date, true) }}
            </template>
        </Column>
        <Column field="" header="Caja">
            <template #body="slot">
                <span class="bg-gray-200 py-2 px-3 rounded-full mr-2 text-gray-500">
                    <i class="pi pi-shopping-cart"></i>
                </span>
                {{ slot.data.cash_register.name }}
            </template>
        </Column>
        <Column field="total_entries" header="Total de entradas">
            <template #body="slot">
                <Tag severity="success">
                    {{ formatMoneyNumber(slot.data.total_entries) }}
                </Tag>
            </template>
        </Column>
        <Column field="total_exits" header="Total de salidas">
            <template #body="slot">
                <Tag severity="danger">
                    {{ formatMoneyNumber(slot.data.total_exits) }}
                </Tag>
            </template>
        </Column>
        <Column field="final_balance" header="Balance final">
            <template #body="slot">
                <Tag severity="info">{{ formatMoneyNumber(slot.data.final_balance) }}</Tag>
            </template>
        </Column>
        <Column field="" header="">
            <template #header>
                <Button rounded raised @click="showModalCreate" icon="pi pi-plus" severity="success"></Button>
            </template>
        </Column>
    </DataTable>
</template>