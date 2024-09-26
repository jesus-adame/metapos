<script setup lang="ts">
import { can, formatDateTime, formatMoneyNumber, getPrinter, saleSeverity, saleStatus } from '@/helpers';
import Column from 'primevue/column';
import CashRegisterIcon from '@/Components/icons/CashRegisterIcon.vue';
import UserIcon from '@/Components/icons/UserIcon.vue';
import { onMounted, ref } from 'vue';
import { AxiosResponse } from 'axios';
import { Link } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import PDFObject from '../PDFObject.vue';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import Tag from 'primevue/tag';
import { useSaleStore } from '@/stores/SaleStore';
import PrintTicketButton from '../prints/PrintTicketButton.vue';

const modalTicket = ref<boolean>(false)
const saleId = ref<number | null>(null)
const confirm = useConfirm()
const toast = useToast()
const saleStore = useSaleStore()
const pdfUrl = ref('');

const openModaTicket = (id: number) => {
    saleId.value = id
    modalTicket.value = true
    pdfUrl.value = route('sales.ticket-download', {id: saleId.value})
}

const confirmDelete = (url: string) => {
    confirm.require({
        header: '¿Está seguro de cancelar?',
        message: 'Se cancelará la venta y cargara los productos al inventario',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancelar',
            severity: 'secondary',
        },
        acceptProps: {
            label: 'Aplicar cancelación',
            severity: 'danger'
        },
        accept: () => {
            saleStore.deleteItem(url)
            .then((response: AxiosResponse) => {
                toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 1500 })
                saleStore.fetchItems()
            })
            .catch(reject => {
                console.error(reject.response.data.errors);
                toast.add({ summary: 'Error', detail: reject.response.data.message, severity: 'error', life: 3000 })
            })
        }
    });
}

onMounted(() => {
    saleStore.fetchItems()
})
</script>
<template>
    <Dialog v-model:visible="modalTicket" modal :header="'Venta #' + saleId">
        <div class="flex gap-4 w-full items-center justify-end pt-2">
            Mandar a imprimir <PrintTicketButton :pdf-url="pdfUrl" :printer="getPrinter()"></PrintTicketButton>
        </div>
    </Dialog>

    <DataTable :value="saleStore.sales" paginator :rows="saleStore.rows" :total-records="saleStore.totalRecords" @page="saleStore.onPage" lazy>
        <Column field="id" header="#">
            <template #body="{data}">
                <Link :href="route('sales.show', { sale: data.id })">
                    {{ data.id }}
                </Link>
            </template>
        </Column>
        <Column header="Fecha y hora">
            <template #body="{data}">
                {{ formatDateTime(data.created_at) }}
            </template>
        </Column>
        <Column header="Estatus">
            <template #body="{data}">
                <Tag :severity="saleSeverity(data.status)" :value="saleStatus(data.status)"></Tag>
            </template>
        </Column>
        <Column header="Cliente">
            <template #body="{data}">
                <UserIcon>
                    {{ data.customer?.name || '-' }} {{ data.customer?.lastname }}
                </UserIcon>
            </template>
        </Column>
        <Column header="Vendedor">
            <template #body="{data}">
                <UserIcon>
                    {{ data.seller.name }} {{ data.seller.lastname }}
                </UserIcon>
            </template>
        </Column>
        <Column header="Caja">
            <template #body="{data}">
                <CashRegisterIcon>
                    {{ data.cash_register?.name || '-' }}
                </CashRegisterIcon>
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
                <div class="flex gap-1">
                    <Link :href="route('sales.show', {sale: data.id})">
                        <Button raised icon="pi pi-eye" severity="info" v-tooltip.top="'Ver'"></Button>
                    </Link>
                    <Button v-if="can('update sales')" raised icon="pi pi-replay" severity="danger" v-tooltip.top="'Cancelar'" @click="confirmDelete(route('api.sales.cancel', {sale: data.id}))"></Button>
                    <Button raised icon="pi pi-print" v-tooltip.top="'Imprimir'" @click="openModaTicket(data.id)"></Button>
                </div>
            </template>
        </Column>
    </DataTable>
</template>