<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import ListSales from '@/Components/tables/ListSales.vue';
import DatePicker from 'primevue/datepicker';
import { ref, watch } from 'vue';
import { useAuthStore } from '@/stores/AuthStore';
import { useSaleStore } from '@/stores/SaleStore';
import { CashRegister } from '@/types';
import { formatMoneyNumber } from '@/helpers';

const dates = ref([])
const authStore = useAuthStore()
const selectedCashRegister = ref<CashRegister | null>(null)
const selectedStatus = ref<string | null>(null)
const saleStore = useSaleStore()

watch(dates, (dates) => {
    saleStore.setDates(dates)
    saleStore.fetchItems()
})

watch(selectedCashRegister, (cashRegister) => {
    saleStore.setCashRegister(cashRegister)
    saleStore.fetchItems()
})

watch(selectedStatus, (status) => {
    saleStore.setStatus(status)
    saleStore.fetchItems()
})

const clearCashRegister = () => {
    selectedCashRegister.value = null
}

const clearStatus = () => {
    selectedStatus.value = null
}
</script>
<template>
    <Head title="Ventas" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ventas</h2>
        </template>

        <div class="mt-4 mb-4 flex justify-between">
            <div>
                <Link :href="route('sales.create')">
                    <Button raised label="Nueva venta" severity="success" icon="pi pi-plus"></Button>
                </Link>
                <Link :href="route('customers.index')" class="ml-2">
                    <Button raised label="Clientes" severity="info" icon="pi pi-users"></Button>
                </Link>
            </div>
            <div class="flex gap-2 items-center">
                <span class="bg-green-200 text-green-700 py-2 px-3 rounded"><strong>{{ formatMoneyNumber(saleStore.totalSales) }}</strong></span>
                <DatePicker v-model="dates" selectionMode="range" :manualInput="false" dateFormat="dd/mm/yy" showButtonBar placeholder="dd/mm/yyyy - dd/mm/yyyy" />
                <div class="flex gap-2">
                    <Select class="w-30" v-model="selectedStatus" :options="saleStore.statusList || []" optionLabel="label" optionValue="value" placeholder="Estatus"></Select>
                    <Button v-if="selectedStatus" @click="clearStatus" severity="danger" icon="pi pi-times" text></Button>
                </div>
                <div class="flex gap-2">
                    <Select class="w-30" v-model="selectedCashRegister" :options="authStore?.cashRegisters || []" optionLabel="name" placeholder="Elegir caja"></Select>
                    <Button v-if="selectedCashRegister" @click="clearCashRegister" severity="danger" icon="pi pi-times" text></Button>
                </div>
            </div>
        </div>
        <ListSales></ListSales>
    </UserLayout>
</template>