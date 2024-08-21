<script setup lang="ts">
import { formatDate, formatMoneyNumber } from '@/helpers';
import Column from 'primevue/column';
import DataTable, { DataTablePageEvent } from 'primevue/datatable';
import Tag from 'primevue/tag';
import { onMounted, ref, watch } from 'vue';
import { AxiosResponse } from 'axios';
import { CashFlow } from '@/types';
import Dialog from 'primevue/dialog';
import CreateCashMovement from '../forms/CreateCashMovement.vue';
import Button from 'primevue/button';
import CashFlowService from '@/Services/CashFlowService';
import CashRegisterIcon from '@/Components/icons/CashRegisterIcon.vue';
import { useAuthStore } from '@/stores/AuthStore';

const cashFlowService = new CashFlowService;
const modalCashMovements = ref(false)

const items = ref<CashFlow[]>([]);
const rows = ref(5);
const current_page = ref(1);
const totalRecords = ref(0)
const authStore = useAuthStore()

const calculateSeverity = (flow: CashFlow) => {
    switch (flow.type) {
        case 'entry': {
            return 'success';
        }
        case 'exit': {
            return 'danger';
        }
    }
}

const calculateIcon = (flow: CashFlow) => {
    switch (flow.type) {
        case 'entry': {
            return 'pi pi-angle-up';
        }
        case 'exit': {
            return 'pi pi-angle-down';
        }
    }
}

const calculateLabel = (flow: CashFlow) => {
    switch (flow.type) {
        case 'entry': {
            return 'Entrada';
        }
        case 'exit': {
            return 'Salida';
        }
    }
}

const calculateMetodLabel = (method: string) => {
    switch (method) {
        case 'cash': {
            return 'Efectivo';
        }
        case 'card': {
            return 'Tarjeta';
        }
        case 'transfer': {
            return 'Transferencia';
        }
    }
}

const calculateMetodIcon = (method: string) => {
    switch (method) {
        case 'cash': {
            return 'pi-money-bill';
        }
        case 'card': {
            return 'pi-credit-card';
        }
        case 'transfer': {
            return 'pi-arrow-right';
        }
    }
}

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchItems(pageNumber);
}

const fetchItems = (pageNumber: number) => {
    cashFlowService.paginate(pageNumber, rows.value)
    .then((response: AxiosResponse) => {
        const paginate = response.data.paginate
        totalRecords.value = paginate.total
        items.value = paginate.data
    })
}

onMounted(() => {
    fetchItems(current_page.value)
})

const showModalMovements = () => {
    modalCashMovements.value = true
}

const hideModalMovements = () => {
    modalCashMovements.value = false
    fetchItems(current_page.value)
}

watch(() => authStore.cashRegister, () => {
    fetchItems(current_page.value)
})
</script>
<template>
    <Dialog v-model:visible="modalCashMovements" modal header="Registrar movimiento" :style="{ width: '35rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <CreateCashMovement class="mt-4" @save="hideModalMovements">
            <Button raised label="Cancelar" @click="hideModalMovements" class="ml-2"></Button>
        </CreateCashMovement>
    </Dialog>
    <DataTable :value="items" paginator :rows="rows" @page="onPage" :totalRecords="totalRecords" lazy>
        <Column field="id" header="#"></Column>
        <Column field="date" header="Fecha">
            <template #body="slot">
                {{ formatDate(slot.data.date, true) }}
            </template>
        </Column>
        <Column header="Tipo">
            <template #body="slot">
                <Tag :value="calculateLabel(slot.data)" :severity="calculateSeverity(slot.data)" :icon="calculateIcon(slot.data)"></Tag>
            </template>
        </Column>
        <Column header="Método">
            <template #body="slot">
                <i class="pi mr-3 text-lg text-blue-600" :class="calculateMetodIcon(slot.data.method)"></i>
                <span class="font-bold">{{ calculateMetodLabel(slot.data.method) }}</span>
            </template>
        </Column>
        <Column field="cash_register" header="Caja">
            <template #body="slot">
                <div class="flex items-center">
                    <CashRegisterIcon/>
                    <span>
                        {{ slot.data.cash_register?.name || 'NA' }}
                    </span>
                </div>
            </template>
        </Column>
        <Column field="amount" header="Cantidad">
            <template #body="slot">
                {{ formatMoneyNumber(slot.data.amount) }}
            </template>
        </Column>
        <Column field="description" header="Descripción"></Column>
        <Column field="" header="">
            <template #header>
                <Button rounded raised @click="showModalMovements" severity="success" icon="pi pi-plus"></Button>
            </template>
        </Column>
    </DataTable>
</template>