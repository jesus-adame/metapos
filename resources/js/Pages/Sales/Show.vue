<script setup lang="ts">
import Card from '@/Components/Card.vue';
import AddSaleCustomer from '@/Components/forms/AddSaleCustomer.vue';
import { calculateMetodIcon, can, formatMoneyNumber, getPercentage, percentageNumber, saleSeverity, saleStatus } from '@/helpers';
import UserLayout from '@/Layouts/UserLayout.vue';
import { Sale } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Tag from 'primevue/tag';
import { ref } from 'vue';

defineProps<{
    sale: Sale,
}>();

const addCustomerModal = ref(false)

const savedCustomer = () => {
    hideAddCustomerModal()
    router.reload()
}

const showAddCustomerModal = () => {
    addCustomerModal.value = true
}

const hideAddCustomerModal = () => {
    addCustomerModal.value = false
}
</script>

<template>
    <Head title="Venta" />

    <Dialog v-model:visible="addCustomerModal" header="Agregar cliente" modal >
        <AddSaleCustomer :sale="sale" @save="savedCustomer"></AddSaleCustomer>
    </Dialog>

    <UserLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Venta # {{ sale.id }}</h2>
                <Link :href="route('sales.index')">
                    <Button icon="pi pi-chevron-left"></Button>
                </Link>
            </div>
        </template>

        <div class="flex mt-4">
            <div class="w-2/3">
                <Card class="mb-4 text-lg">
                    <div v-if="sale.customer" class="flex gap-2 items-center">
                        <strong>Cliente</strong>
                        <span>{{ sale.customer?.name }} {{ sale.customer?.lastname }}</span>
                    </div>
                    <div v-else class="flex gap-2 items-center">
                        <strong>Cliente</strong>
                        <Button v-if="can('update sales')" label="Sin asignar" link @click="showAddCustomerModal"></Button>
                        <span v-else>Sin asignar</span>
                    </div>
                    <div>
                        <strong>Vendedor</strong> {{ sale.seller?.name }} {{ sale.seller?.lastname }}
                    </div>
                    <div>
                        <strong>Estatus</strong> <Tag :severity="saleSeverity(sale.status)" :value="saleStatus(sale.status)"></Tag>
                    </div>
                    <div>
                        <strong>Caja</strong> <span>{{ sale.cash_register?.name }}</span>
                    </div>
                    <div class="flex gap-2 items-center">
                        <strong>Total Venta</strong>
                        <span class="font-bold text-gray-600">{{ formatMoneyNumber(sale.total) }}</span>
                    </div>
                    <div class="flex gap-2 items-center">
                        <strong>Cambio</strong>
                        <span class="font-bold text-gray-600">{{ formatMoneyNumber(sale.change) }}</span>
                    </div>
                </Card>
                <Card padding="0">
                    <DataTable :value="sale.products">
                        <Column field="name" header="Producto">
                            <template #body="{data}">
                                <div class="flex">
                                    <div v-if="data.image" class="overflow-hidden hidden lg:block shadow-lg rounded-md w-16 h-16">
                                        <Image :src="data.image_url" :alt="data.name" />
                                    </div>
                                    <div class="text-left ml-5">
                                        <span class="font-bold">{{ data.name }}</span>
                                        <p class="text-sm">{{ data.code }}</p>
                                        <p class="text-sm">SKU {{ data.sku }}</p>
                                    </div>
                                </div>
                            </template>
                        </Column>
                        <Column header="Precio (Sin IVA)">
                            <template #body="{data}">
                                {{ formatMoneyNumber(data.pivot.price) }}
                            </template>
                        </Column>
                        <Column header="Cantidad">
                            <template #body="slot">
                                {{ slot.data.pivot.quantity }}
                            </template>
                        </Column>
                        <Column header="IVA">
                            <template #body="{data}">
                                {{ percentageNumber(data.pivot.tax) ?? 'N/A' }}
                            </template>
                        </Column>
                        <Column header="Subtotal">
                            <template #body="{data}">
                                {{ formatMoneyNumber(data.pivot.price * data.pivot.quantity) }}
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
                            <template #body="{data}">
                                <i class="pi mr-3 text-lg text-blue-700" :class="calculateMetodIcon(data.payment_method)"></i>
                                {{ data.payment_method.name }}
                            </template>
                        </Column>
                        <Column header="Cantidad">
                            <template #body="{data}">
                                {{ formatMoneyNumber(data.amount) }}
                            </template>
                        </Column>
                    </DataTable>
                </Card>
            </div>
        </div>
    </UserLayout>
</template>