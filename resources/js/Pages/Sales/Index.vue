<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { formatDateTime, formatMoneyNumber } from '@/helpers';
import UserLayout from '@/Layouts/UserLayout.vue';
import Card from '@/Components/Card.vue';
import Column from 'primevue/column';
import Button from 'primevue/button';

defineProps({
    title: {
        type: String
    },
    sales: {
        type: Array
    },
});

const printTicket = async (saleId) => {
    const url = route('sales.ticket', saleId);
    window.open(url, '_blank', 'popup')
};
</script>
<template>
    <Head title="Dashboard" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ title }}</h2>
        </template>

        <div class="mt-6 mb-4">
            <Link :href="route('sales.create')">
                <Button label="Nueva venta" severity="success" icon="pi pi-plus"></Button>
            </Link>
        </div>
        <Card padding="0">
            <DataTable :value="sales" paginator :rows="8">
                <Column field="id" header="#"></Column>
                <Column header="Fecha">
                    <template #body="slot">
                        {{ formatDateTime(slot.data.created_at) }}
                    </template>
                </Column>
                <Column header="Cliente">
                    <template #body="slot">
                        <div class="flex">
                            <div class="rounded-full bg-gray-200 px-2 py-1 mr-2">
                                <i class="pi pi-user"></i>
                            </div>
                            {{ slot.data.customer?.first_name || 'Sin asignar' }} {{ slot.data.customer?.last_name }}
                        </div>
                    </template>
                </Column>
                <Column header="Vendedor">
                    <template #body="slot">
                        <div class="flex">
                            <div class="rounded-full bg-gray-200 px-2 py-1 mr-2">
                                <i class="pi pi-user"></i>
                            </div>
                            {{ slot.data.seller.name }}
                        </div>
                    </template>
                </Column>
                <Column header="Caja">
                    <template #body="slot">
                        <div class="flex">
                            <div class="rounded-full bg-gray-200 px-2 py-1 mr-2">
                                <i class="pi pi-building"></i>
                            </div>
                            {{ slot.data.cash_register?.name || 'Sin asignar' }}
                        </div>
                    </template>
                </Column>
                <Column field="total" header="Total">
                    <template #body="slot">
                        {{ formatMoneyNumber(slot.data.total) }}
                    </template>
                </Column>
                <Column header="">
                    <template #body="slot">
                        <div class="flex">
                            <Link :href="route('sales.show', {sale: slot.data.id})">
                                <Button icon="pi pi-eye" class="mr-1"></Button>
                            </Link>
                            <Button icon="pi pi-print" @click="printTicket(slot.data.id)"></Button>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </Card>
    </UserLayout>
</template>