<script lang="ts" setup>
import { onMounted, ref, watch } from 'vue';
import CurrencyService from "@/Services/CurrencyService";
import axios, { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';
import { formatDateTime } from '@/helpers';
import { useCurrencyStore } from '@/stores/CurrencyStore';
import Button from 'primevue/button';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

const currencyService = new CurrencyService()
const items = ref([])
const rows = ref(15)
const totalRecords = ref(0)
const page = ref(1)
const currencyStore = useCurrencyStore()
const confirm = useConfirm()
const toast = useToast()

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

const refreshItems = (url: string) => {
    axios.post(url)
    .then((response: AxiosResponse) => {
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 1500 })
    })
    .catch(reject => {
        console.error(reject.response.data.errors);
        toast.add({ summary: 'Error', detail: reject.response.data.message, severity: 'error', life: 3000 })
    })
}

const confirmRefresh = (url: string) => {
    confirm.require({
        header: '¿Está seguro?',
        message: 'Se actualizarán los tipos de cambio',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancelar',
            severity: 'secondary',
        },
        acceptProps: {
            label: 'Actualizar',
            severity: 'info'
        },
        accept: () => {
            refreshItems(url)
        }
    });
}
</script>
<template>
    <div class="mb-2">
        <Button :label="'Actualizar tipos'" raised @click="confirmRefresh(route('api.currencies.refresh-rates'))"></Button>
    </div>
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