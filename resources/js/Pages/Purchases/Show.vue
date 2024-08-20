<script setup lang="ts">
import Card from '@/Components/Card.vue';
import { formatMoneyNumber, purchaseStatus } from '@/helpers';
import UserLayout from '@/Layouts/UserLayout.vue';
import { Purchase } from '@/types';
import { Head } from '@inertiajs/vue3';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Image from 'primevue/image';

defineProps<{
    purchase: Purchase,
}>();
</script>

<template>
    <Head title="Compra" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Compra # {{ purchase.id }}</h2>
        </template>

        <div class="flex mt-4">
            <div class="w-2/3">
                <Card class="mb-4 text-lg">
                    <div>
                        <strong>Compra #</strong> {{ purchase.id }}
                    </div>
                    <div>
                        <strong>Proveedor</strong> {{ purchase.supplier?.firstname }} {{ purchase.supplier?.lastname }}
                    </div>
                    <div>
                        <strong>Estatus</strong> {{ purchaseStatus(purchase.status) }}
                    </div>
                    <div>
                        <strong>Caja</strong> {{ purchase?.cash_register?.name }}
                    </div>
                    <div>
                        <strong>Total Compra</strong> {{ formatMoneyNumber(purchase.total) }}
                    </div>
                </Card>
                <DataTable :value="purchase.products">
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
            </div>
        </div>
    </UserLayout>
</template>