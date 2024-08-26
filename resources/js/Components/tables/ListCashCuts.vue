<script lang="ts" setup>
import axios, { AxiosResponse } from 'axios';
import Column from 'primevue/column';
import DataTable, { DataTablePageEvent } from 'primevue/datatable';
import { onMounted, ref, watch } from 'vue';
import CashCutService from "@/Services/CashCutService";
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import CreateCashCut from '../forms/CreateCashCut.vue';
import { formatDate, formatMoneyNumber } from '@/helpers';
import CashRegisterIcon from '../icons/CashRegisterIcon.vue';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import { useAuthStore } from '@/stores/AuthStore';

const page = ref<number>(1)
const rows = ref<number>(6)
const items = ref([])
const cashCutService = new CashCutService()
const modalCreate = ref(false)
const totalRecords = ref(0)
const toast = useToast()
const confirm = useConfirm()
const authStore = useAuthStore()

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

const deleteItem = (url: string) => {
    axios.post(url, { _method: 'delete' })
    .then((response: AxiosResponse) => {
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 1500 })
        fetchItems(page.value)
    })
    .catch(reject => {
        console.error(reject.response.data.errors);
        toast.add({ summary: 'Error', detail: reject.response.data.message, severity: 'error', life: 3000 })
    })
}

const confimDelete = (url: string) => {
    confirm.require({
        header: '¿Está seguro?',
        message: 'Se eliminará el corte de caja',
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

watch(() => authStore.cashRegister, () => {
    fetchItems(page.value)
})
</script>
<template>
    <Dialog v-model:visible="modalCreate" header="Nuevo corte" modal :style="{ width: '35rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <CreateCashCut @save="hideModalCreate"></CreateCashCut>
    </Dialog>

    <DataTable :value="items" paginator :rows="rows" :totalRecords="totalRecords" @page="onPage" lazy>
        <Column field="cut_date" header="Fecha de corte">
            <template #body="{data}">
                {{ formatDate(data.cut_date) }} - {{ formatDate(data.cut_end_date) }}
            </template>
        </Column>
        <Column field="" header="Caja">
            <template #body="{data}">
                <div class="flex">
                    <CashRegisterIcon>
                        {{ data.cash_register.name }}
                    </CashRegisterIcon>
                </div>
            </template>
        </Column>
        <Column field="cash_amount" header="Efectivo">
            <template #body="{data}">
                <Tag severity="info">{{ formatMoneyNumber(data.cash_amount) }}</Tag>
            </template>
        </Column>
        <Column field="card_amount" header="Tarjeta">
            <template #body="{data}">
                <Tag severity="info">{{ formatMoneyNumber(data.card_amount) }}</Tag>
            </template>
        </Column>
        <Column field="transfer_amount" header="Transferencia">
            <template #body="{data}">
                <Tag severity="info">{{ formatMoneyNumber(data.transfer_amount) }}</Tag>
            </template>
        </Column>
        <Column field="total_entries" header="Total de entradas">
            <template #body="{data}">
                <Tag severity="success">
                    {{ formatMoneyNumber(data.total_entries) }}
                </Tag>
            </template>
        </Column>
        <Column field="total_exits" header="Total de salidas">
            <template #body="{data}">
                <Tag severity="danger">
                    {{ formatMoneyNumber(data.total_exits) }}
                </Tag>
            </template>
        </Column>
        <Column field="card_amount" header="Balance">
            <template #body="{data}">
                <Tag severity="info">{{ formatMoneyNumber(data.final_balance) }}</Tag>
            </template>
        </Column>
        <Column field="" header="">
            <template #header>
                <Button rounded raised @click="showModalCreate" icon="pi pi-plus" severity="success"></Button>
            </template>
            <template #body="{data}">
                <Button icon="pi pi-trash" severity="danger" raised @click="confimDelete(route('api.cash-cuts.destroy', { cash_cut: data.id}))"></Button>
            </template>
        </Column>
    </DataTable>
</template>