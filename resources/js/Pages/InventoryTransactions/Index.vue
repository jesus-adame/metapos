<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import ListInventoryTransactions from '@/Components/tables/ListInventoryTransactions.vue';
</script>
<template>
    <Head title="Movimientos de Inventario" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Movimientos de Inventario</h2>
        </template>

        <div class="mb-4 mt-6">
            <Link :href="route('products.index')">
                <Button label="Productos" icon="pi pi-box"></Button>
            </Link>
        </div>

        <ListInventoryTransactions></ListInventoryTransactions>
    </UserLayout>
</template>
