<script lang="ts" setup>
import { AxiosResponse } from 'axios';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable, { DataTablePageEvent } from 'primevue/datatable';
import { onMounted, ref } from 'vue';
import CashRegisterService from "@/Services/CashRegisterService";
import CreateCashRegister from '../forms/CreateCashRegister.vue';
import { formatDateTime } from '@/helpers';

const cashRegisterService: CashRegisterService = new CashRegisterService()
const modalCreate = ref(false)
const items = ref([])
const rows = ref(10)
const totalRecords = ref(0)
const page = ref(1)

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
</script>
<template>
    <DataTable :value="items" paginator :rows="rows" lazy @page="onPage" :totalRecords="totalRecords">
        <Column field="id" header="#"></Column>
        <Column field="name" header="Nombre">
            <template #body="slot">
                <span>{{ slot.data.name }}</span>
                <Tag class="ml-2" v-if="slot.data.is_default" :value="'default'" severity="info"></Tag>
            </template>
        </Column>
        <Column field="branch" header="Ubicación">
            <template #body="slot">
                <div class="flex items-center">
                    <div class="rounded-full px-3 py-2 bg-gray-200 mr-2">
                        <i class="pi pi-building"></i>
                    </div>
                    <span>{{ slot.data.branch.name }}</span>
                </div>
            </template>
        </Column>
        <Column field="created_at" header="Creado">
            <template #body="slot">
                {{ formatDateTime(slot.data.created_at) }}
            </template>
        </Column>
        <Column field="" header="">
            <template #header>
                <Button icon="pi pi-plus" rounded severity="success" raised @click="showModalCreate"></Button>
            </template>
        </Column>
    </DataTable>

    <Dialog v-model:visible="modalCreate" modal header="Registrar caja" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <CreateCashRegister class="mt-4" @save="hideModalCreate"></CreateCashRegister>
    </Dialog>
</template>