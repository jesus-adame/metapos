<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import ListCustomers from '@/Components/tables/ListCustomers.vue';
import Button from 'primevue/button';

</script>

<template>
    <Head title="Clientes" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clientes</h2>
        </template>
        <div class="my-4">
            <Link :href="route('sales.index')">
                <Button raised class="mb-4" label="Ventas" severity="info" icon="pi pi-money-bill"></Button>
            </Link>
            <ListCustomers></ListCustomers>
        </div>
    </UserLayout>
</template>