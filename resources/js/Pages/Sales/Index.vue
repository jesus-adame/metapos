<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import ListSales from '@/Components/tables/ListSales.vue';
</script>
<template>
    <Head title="Ventas" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ventas</h2>
        </template>

        <div class="mt-6 mb-4">
            <Link :href="route('sales.create')">
                <Button label="Nueva venta" severity="success" icon="pi pi-plus"></Button>
            </Link>
            <Link :href="route('customers.index')" class="ml-2">
                <Button label="Clientes" severity="info" icon="pi pi-users"></Button>
            </Link>
        </div>
        <ListSales></ListSales>
    </UserLayout>
</template>