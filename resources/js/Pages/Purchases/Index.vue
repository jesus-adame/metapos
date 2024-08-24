<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import { can } from '@/helpers';
import ListPurchases from '@/Components/tables/ListPurchases.vue';

</script>
<template>
    <Head title="Compras" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Compras</h2>
        </template>

        <div class="mt-6 mb-4">
            <Link v-if="can('create purchases')" :href="route('purchases.create')" class="mr-2">
                <Button label="Nueva compra" icon="pi pi-plus" severity="success"></Button>
            </Link>
            <Link v-if="can('view purchases')" :href="route('suppliers.index')">
                <Button label="Proveedores" icon="pi pi-users" severity="info"></Button>
            </Link>
        </div>

        <div class="mt-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <ListPurchases></ListPurchases>
        </div>
    </UserLayout>
</template>
