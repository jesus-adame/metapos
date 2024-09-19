<script setup lang="ts">
import { can, formatDateTime, formatMoneyNumber, saleSeverity, saleStatus } from '@/helpers';
import Column from 'primevue/column';
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
import { useQuoteStore } from '@/stores/QuoteStore';

const modalTicket = ref<boolean>(false)
const saleId = ref<number | null>(null)
const confirm = useConfirm()
const toast = useToast()
const quoteStore = useQuoteStore()

const openModaTicket = (id: number) => {
    saleId.value = id
    modalTicket.value = true
}

onMounted(() => {
    quoteStore.fetchItems()
})

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
            quoteStore.deleteItem(url)
            .then((response: AxiosResponse) => {
                toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 1500 })
                quoteStore.fetchItems()
            })
            .catch(reject => {
                console.error(reject.response.data.errors);
                toast.add({ summary: 'Error', detail: reject.response.data.message, severity: 'error', life: 3000 })
            })
        }
    });
}
</script>
<template>
    <Dialog v-model:visible="modalTicket" modal :header="'Venta #' + saleId">
        <PDFObject :url="route('sales.ticket', {id: saleId})" :options="{ height: '100vh', width: '30vw', border: '1px', solid: '#ccc' }" />
    </Dialog>

    <DataTable :value="quoteStore.sales" paginator :rows="quoteStore.rows" :total-records="quoteStore.totalRecords" @page="quoteStore.onPage" lazy>
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