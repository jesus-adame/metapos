<script setup lang="ts">
import Card from '@/Components/Card.vue';
import AddPurchaseSupplier from '@/Components/forms/AddPurchaseSupplier.vue';
import { calculateMetodIcon, can, formatMoneyNumber, getPaymentName, purchaseSeverity, purchaseStatus } from '@/helpers';
import UserLayout from '@/Layouts/UserLayout.vue';
import { Purchase } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Image from 'primevue/image';
import Tag from 'primevue/tag';
import { ref } from 'vue';

defineProps<{
    purchase: Purchase,
}>();

const addSupplierModal = ref(false)

const savedSupplier = () => {
    hideAddSupplierModal()
    router.reload()
}

const showAddSupplierModal = () => {
    addSupplierModal.value = true
}

const hideAddSupplierModal = () => {
    addSupplierModal.value = false
}
</script>

<template>
    <Head title="Compra" />

    <Dialog v-model:visible="addSupplierModal" header="Agregar proveedor" modal >
        <AddPurchaseSupplier :purchase="purchase" @save="savedSupplier"></AddPurchaseSupplier>
    </Dialog>

    <UserLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Compra # {{ purchase.id }}</h2>
                <Link :href="route('purchases.index')">
                    <Button icon="pi pi-chevron-left"></Button>
                </Link>
            </div>
        </template>

        <div class="flex gap-4 mt-4">
            <div class="w-2/3">
                <Card class="mb-4 text-lg">
                    <div>
                        <strong>Compra #</strong> {{ purchase.id }}
                    </div>
                    <div v-if="purchase.supplier" class="flex gap-2 items-center">
                        <strong>Proveedor</strong>
                        <span>
                            {{ purchase.supplier?.name }} {{ purchase.supplier?.lastname }}
                        </span>
                    </div>
                    <div v-else class="flex gap-2 items-center">
                        <strong>Proveedor</strong>
                        <Button v-if="can('update purchases')" label="Sin asignar" link @click="showAddSupplierModal"></Button>
                        <span v-else>Sin asignar</span>
                    </div>
                    <div>
                        <strong>Estatus</strong>  <Tag :severity="purchaseSeverity(purchase.status)" :value="purchaseStatus(purchase.status)"></Tag>
                    </div>
                    <div>
                        <strong>Ubicación</strong> {{ purchase?.location?.name }}
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
            <div class="w-1/3">
                <Card padding="0">
                    <p class="text-lg font-bold px-6 py-4">Pago</p>
                    <DataTable :value="purchase.payments">
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