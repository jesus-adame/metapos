<script lang="ts" setup>
import axios, { AxiosResponse } from 'axios';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable, { DataTablePageEvent } from 'primevue/datatable';
import { onMounted, ref } from 'vue';
import CashRegisterService from "@/Services/CashRegisterService";
import CreateCashRegister from '../forms/CreateCashRegister.vue';
import { formatDate } from '@/helpers';
import CashRegisterIcon from '../icons/CashRegisterIcon.vue';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

const cashRegisterService: CashRegisterService = new CashRegisterService()
const modalCreate = ref(false)
const items = ref([])
const rows = ref(10)
const totalRecords = ref(0)
const page = ref(1)
const confirm = useConfirm()
const toast = useToast()

const showModalCreate = () => {
    modalCreate.value = true
}

const hideModalCreate = () => {
    modalCreate.value = false
    fetchItems(page.value)
}

const fetchItems = (pageNumber: number) => {
    cashRegisterService.paginate(pageNumber, rows.value)
    .then((response: AxiosResponse) => {
        const cashRegisters = response.data;

        totalRecords.value = cashRegisters.total
        items.value = JSON.parse(JSON.stringify(cashRegisters.data))
        page.value = cashRegisters.current_page
    })
}

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchItems(pageNumber);
}

onMounted(() => {
    fetchItems(page.value)
})

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

const confirmDelete = (url: string) => {
    confirm.require({
        header: '¿Está seguro?',
        message: 'Se eliminará la caja registradora',
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
</script>
<template>
    <Dialog v-model:visible="modalCreate" modal header="Registrar caja" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <CreateCashRegister class="mt-4" @save="hideModalCreate"></CreateCashRegister>
    </Dialog>

    <DataTable :value="items" paginator :rows="rows" lazy @page="onPage" :totalRecords="totalRecords">
        <Column field="id" header="#"></Column>
        <Column field="name" header="Nombre">
            <template #body="{data}">
                <CashRegisterIcon>
                    <span>{{ data.name }}</span>
                    <Tag class="ml-2" v-if="data.is_default" :value="'default'" severity="info"></Tag>
                </CashRegisterIcon>
            </template>
        </Column>
        <Column field="location" header="Ubicación">
            <template #body="{data}">
                <div class="flex items-center">
                    <div class="rounded-full px-3 py-2 bg-gray-200 mr-2">
                        <i class="pi pi-building"></i>
                    </div>
                    <span>{{ data.location.name }}</span>
                </div>
            </template>
        </Column>
        <Column field="created_at" header="Creado">
            <template #body="{data}">
                {{ formatDate(data.created_at) }}
            </template>
        </Column>
        <Column field="" header="">
            <template #header>
                <Button icon="pi pi-plus" rounded severity="success" raised @click="showModalCreate"></Button>
            </template>
            <template #body="{data}">
                <Button icon="pi pi-trash" raised severity="danger" @click="confirmDelete(route('api.cash-registers.destroy', {cash_register: data.id}))"></Button>
            </template>
        </Column>
    </DataTable>
</template>