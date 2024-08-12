<script setup lang="ts">
import Card from '@/Components/Card.vue';
import { formatMoneyNumber, saleStatus } from '@/helpers';
import UserLayout from '@/Layouts/UserLayout.vue';
import { Payment, Sale } from '@/types';
import { Head } from '@inertiajs/vue3';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';

defineProps<{
    sale: Sale,
}>();

const getPaymentName = (payment: Payment) => {
    switch (payment.method) {
        case 'cash': {
            return 'Efectivo'
        }
        case 'card': {
            return 'Tarjeta de crédito/débito'
        }
        case 'transfer': {
            return 'Transferencia'
        }
    }
}

const calculateMetodIcon = (payment: Payment) => {
    switch (payment.method) {
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
</script>

<template>
    <Head title="Venta" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Venta # {{ sale.id }}</h2>
        </template>

        <div class="flex mt-4">
            <div class="w-2/3">
                <Card class="mb-4 text-lg">
                    <div>
                        <strong>Cliente</strong> {{ sale.customer?.first_name }} {{ sale.customer?.last_name }}
                    </div>
                    <div>
                        <strong>Vendedor</strong> {{ sale.seller?.name }} {{ sale.seller?.lastname }}
                    </div>
                    <div>
                        <strong>Total Venta</strong> {{ formatMoneyNumber(sale.total) }}
                    </div>
                    <div>
                        <strong>Estatus</strong> {{ saleStatus(sale.status) }}
                    </div>
                    <div>
                        <strong>Caja</strong> {{ sale.cash_register?.name }}
                    </div>
                </Card>
                <Card padding="0">
                    <DataTable :value="sale.products">
                        <Column field="name" header="Producto">
                            <template #body="slot">
                                <div class="flex">
                                    <Image v-if="slot.data.image" :src="slot.data.image_url" :alt="slot.data.name" class="shadow-lg rounded-md overflow-hidden" width="64" />
                                    <div class="text-left ml-5">
                                        <span class="font-bold">{{ slot.data.name }}</span>
                                        <p>{{ slot.data.code }}</p>
                                    </div>
                                </div>
                            </template>
                        </Column>
                        <Column header="Precio">
                            <template #body="slot">
                                {{ formatMoneyNumber(slot.data.pivot.price) }}
                            </template>
                        </Column>
                        <Column header="Cantidad">
                            <template #body="slot">
                                {{ slot.data.pivot.quantity }}
                            </template>
                        </Column>
                        <Column header="Subtotal">
                            <template #body="slot">
                                {{ formatMoneyNumber(slot.data.pivot.price * slot.data.pivot.quantity) }}
                            </template>
                        </Column>
                    </DataTable>
                </Card>
            </div>

            <div class="ml-4 w-1/3">
                <Card padding="0">
                    <p class="text-lg font-bold px-6 py-4">Pago</p>
                    <DataTable :value="sale.payments">
                        <Column header="Nombre">
                            <template #body="slot">
                                <i class="pi mr-3 text-lg text-blue-700" :class="calculateMetodIcon(slot.data)"></i>
                                {{ getPaymentName(slot.data) }}
                            </template>
                        </Column>
                        <Column header="Cantidad">
                            <template #body="slot">
                                {{ formatMoneyNumber(slot.data.amount) }}
                            </template>
                        </Column>
                    </DataTable>
                </Card>
            </div>
        </div>
    </UserLayout>
</template>