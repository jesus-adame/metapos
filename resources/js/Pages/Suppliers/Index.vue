<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';
import ListSuppliers from '@/Components/tables/ListSuppliers.vue';
</script>

<template>
    <Head title="Proveedores" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proveedores</h2>
        </template>

        <div class="my-4">
            <ListSuppliers></ListSuppliers>
        </div>
    </UserLayout>
</template>