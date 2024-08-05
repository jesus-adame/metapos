<script setup>
import { usePage, Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import moment from 'moment/moment';

const { props } = usePage();
const purchases = props.purchases;

const formatNumber = (numb) => {
    return new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(numb);
}
</script>

<template>
    <Head title="Compras" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Compras</h2>
        </template>

        <div class="mt-6 mb-4">
            <Link :href="route('purchases.create')">
                <Button label="Nueva compra" icon="pi pi-plus" severity="success"></Button>
            </Link>
        </div>

        <div class="mt-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <DataTable :value="purchases">
                <Column field="id" header="Id"></Column>
                <Column header="Fecha">
                    <template #body="slot">
                        {{ moment(slot.data.purchase_date).calendar() }}
                    </template>
                </Column>
                <Column header="Proveedor">
                    <template #body="slot">
                        {{ slot.data.supplier.first_name }} {{ slot.data.supplier.last_name }}
                    </template>
                </Column>
                <Column header="Comprador">
                    <template #body="slot">
                        {{ slot.data.supplier.first_name }} {{ slot.data.supplier.last_name }}
                    </template>
                </Column>
                <Column field="total" header="Total">
                    <template #body="slot">
                        {{ formatNumber(slot.data.total) }}
                    </template>
                </Column>
                <Column header="">
                    <template #body="slot">
                        <Link :href="route('purchases.show', {purchase: slot.data.id})">
                           <Button icon="pi pi-eye"></Button>
                        </Link>
                    </template>
                </Column>
            </DataTable>
        </div>
    </UserLayout>
</template>
