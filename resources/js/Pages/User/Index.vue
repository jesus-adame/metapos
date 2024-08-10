<script setup>
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Card from 'primevue/card';
import ListCustomers from '@/Components/tables/ListCustomers.vue';
import ListSuppliers from '@/Components/tables/ListSuppliers.vue';

defineProps({
    title: {
        type: String
    },
    users: {
        type: Array
    },
    customers: {
        type: Array
    },
    suppliers: {
        type: Array
    },
});
</script>

<template>
    <Head :title="title" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ title }}</h2>
        </template>

        <Card class="mb-4">
            <template #title>
                <h3>Clientes</h3>
            </template>

            <template #content>
                <ListCustomers></ListCustomers>
            </template>
        </Card>

        <Card class="mb-4">
            <template #title>
                <h3>Proveedores</h3>
            </template>

            <template #content>
                <ListSuppliers></ListSuppliers>
            </template>
        </Card>
    </UserLayout>
</template>