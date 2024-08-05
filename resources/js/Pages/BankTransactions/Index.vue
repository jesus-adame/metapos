<script setup>
import { usePage, Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Card from '@/Components/Card.vue';

const { props } = usePage();
const transactions = props.transactions;
const balance = props.balance;

const formattedBalance = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(balance);

const calculateSeverity = (transaccion) => {
    switch (transaccion.type) {
        case 'entry': {
            return 'success';
        }
        case 'exit': {
            return 'danger';
        }
    }
}

const calculateIcon = (transaccion) => {
    switch (transaccion.type) {
        case 'entry': {
            return 'pi pi-angle-up';
        }
        case 'exit': {
            return 'pi pi-angle-down';
        }
    }
}

const calculateLabel = (transaccion) => {
    switch (transaccion.type) {
        case 'entry': {
            return 'Entrada';
        }
        case 'exit': {
            return 'Salida';
        }
    }
}

const formatNumber = (numb) => {
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(numb);
}
</script>

<template>
    <Head title="Transferencias Bancarias" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transferencias Bancarias</h2>
        </template>

        <div class="mb-4 mt-6">
            <Link :href="route('bank-transactions.create')">
                <Button label="Nuevo movimiento" icon="pi pi-plus" severity="success"></Button>
            </Link>
        </div>
        <Card padding="0">
            <DataTable :value="transactions">
                <Column field="transaction_date" header="Fecha"></Column>
                <Column header="Tipo">
                    <template #body="slot">
                        <Tag :value="calculateLabel(slot.data)" :severity="calculateSeverity(slot.data)" :icon="calculateIcon(slot.data)"></Tag>
                    </template>
                </Column>
                <Column field="amount" header="Cantidad">
                    <template #body="slot">
                        {{ formatNumber(slot.data.amount) }}
                    </template>
                </Column>
                <Column field="description" header="Descripción"></Column>
            </DataTable>

            <div class="p-4 bg-blue-100 text-blue-500 font-bold">
                <h3>Balance: {{ formattedBalance }}</h3>
            </div>
        </Card>
    </UserLayout>
</template>
