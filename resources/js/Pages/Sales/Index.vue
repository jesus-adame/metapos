<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import ListSales from '@/Components/tables/ListSales.vue';
import DatePicker from 'primevue/datepicker';
import { ref } from 'vue';

const dates = ref([])
</script>
<template>
    <Head title="Ventas" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ventas</h2>
        </template>

        <div class="mt-6 mb-4 flex justify-between">
            <div>
                <Link :href="route('sales.create')">
                    <Button label="Nueva venta" severity="success" icon="pi pi-plus"></Button>
                </Link>
                <Link :href="route('customers.index')" class="ml-2">
                    <Button label="Clientes" severity="info" icon="pi pi-users"></Button>
                </Link>
            </div>
            <DatePicker v-model="dates" selectionMode="range" :manualInput="false" dateFormat="dd/mm/yy" showButtonBar placeholder="dd/mm/yyyy - dd/mm/yyyy" />
        </div>
        <ListSales :dates="dates"></ListSales>
    </UserLayout>
</template>