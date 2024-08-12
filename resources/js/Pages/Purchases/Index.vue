<script setup>
import { usePage, Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import { formatDate, formatMoneyNumber, purchaseStatus } from '@/helpers';
import UserIcon from '@/Components/icons/UserIcon.vue';

const { props } = usePage();
const purchases = props.purchases;
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
                <Column field="id" header="#"></Column>
                <Column header="Fecha">
                    <template #body="slot">
                        {{ formatDate(slot.data.purchase_date) }}
                    </template>
                </Column>
                <Column header="Proveedor">
                    <template #body="slot">
                        <UserIcon>
                            {{ slot.data.supplier?.first_name || 'N/A' }} {{ slot.data.supplier?.last_name }}
                        </UserIcon>
                    </template>
                </Column>
                <Column header="Comprador">
                    <template #body="slot">
                        <UserIcon>
                            {{ slot.data.buyer?.name }} {{ slot.data.buyer?.lastname }}
                        </UserIcon>
                    </template>
                </Column>
                <Column header="Estatus">
                    <template #body="slot">
                        {{ purchaseStatus(slot.data.status) }}
                    </template>
                </Column>
                <Column field="total" header="Total">
                    <template #body="slot">
                        {{ formatMoneyNumber(slot.data.total) }}
                    </template>
                </Column>
                <Column header="">
                    <template #body="slot">
                        <Link :href="route('purchases.show', {purchase: slot.data.id})">
                           <Button icon="pi pi-eye" severity="info"></Button>
                        </Link>
                    </template>
                </Column>
            </DataTable>
        </div>
    </UserLayout>
</template>
