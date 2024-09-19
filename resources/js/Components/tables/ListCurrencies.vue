<script lang="ts" setup>
import { onMounted, ref, watch } from 'vue';
import CurrencyService from "@/Services/CurrencyService";
import { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';
import { formatDateTime, formatMoneyNumber } from '@/helpers';
import { useCurrencyStore } from '@/stores/CurrencyStore';

const currencyService = new CurrencyService()
const items = ref([])
const rows = ref(20)
const totalRecords = ref(0)
const page = ref(1)
const currencyStore = useCurrencyStore()

const fetchItems = (pageNumber: number) => {
    const result = currencyService.paginate(pageNumber, rows.value)

    result.then((response: AxiosResponse) => {
        const currencies = response.data;

        totalRecords.value = currencies.total
        items.value = JSON.parse(JSON.stringify(currencies.data))
        page.value = currencies.current_page
    })
}

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchItems(pageNumber);
}

onMounted(() => {
    fetchItems(page.value)
})

watch(currencyStore.getCurrencies, () => {
    fetchItems(page.value)
})
</script>
<template>
    <DataTable :value="items" class="shadow-md" :paginator="true" :rows="rows" :lazy="true" :totalRecords="totalRecords" @page="onPage">
        <Column field="id" header="#"></Column>
        <Column field="description" header="Moneda">
            <template #body="{data}">
                {{ data.name }}
            </template>
        </Column>
        <Column field="exchange_rate" header="Tipo de cambio">
        </Column>
        <Column field="updated_at" header="Fecha de actualización">
            <template #body="{data}">
                {{ formatDateTime(data.updated_at) }}
            </template>
        </Column>
    </DataTable>
</template>