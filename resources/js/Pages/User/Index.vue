<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import ListCustomers from '@/Components/tables/ListCustomers.vue';
import ListSuppliers from '@/Components/tables/ListSuppliers.vue';

</script>
<template>
    <Head title="Usuarios" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Usuarios</h2>
        </template>

        <div class="mb-4">
            <h3 class="text-xl font-bold my-4">Clientes</h3>
            <ListCustomers></ListCustomers>
        </div>

        <div class="mb-4">
            <h3 class="text-xl font-bold my-4">Proveedores</h3>
            <ListSuppliers></ListSuppliers>
        </div>
    </UserLayout>
</template>