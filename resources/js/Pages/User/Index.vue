<script setup>
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import moment from 'moment/moment';
import Button from 'primevue/button';
import Card from 'primevue/card';

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
                <div class="mb-4">
                    <Link :href="route('customers.create')">
                        <Button label="Nuevo Cliente" icon="pi pi-plus" severity="success"></Button>
                    </Link>
                </div>
                <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="w-full bg-gray-100 text-left text-gray-600">
                        <th class="py-2 px-4 border-b">Nombre</th>
                        <th class="py-2 px-4 border-b">Apellidos</th>
                        <th class="py-2 px-4 border-b">Email</th>
                        <th class="py-2 px-4 border-b">Teléfono</th>
                        <th class="py-2 px-4 border-b">Fecha</th>
                        <th class="py-2 px-4 border-b"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="customer in customers" :key="customer.id" class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b">{{ customer.first_name }}</td>
                        <td class="py-2 px-4 border-b">{{ customer.last_name }}</td>
                        <td class="py-2 px-4 border-b">{{ customer.email }}</td>
                        <td class="py-2 px-4 border-b">{{ customer.phone }}</td>
                        <td class="py-2 px-4 border-b">{{ moment(customer.created_at).calendar() }}</td>
                        <td class="py-2 px-4 border-b">
                            <Button icon="pi pi-trash" severity="danger"></Button>
                        </td>
                    </tr>
                </tbody>
            </table>
            </template>
        </Card>

        <Card class="mb-4">
            <template #title>
                <h3>Proveedores</h3>
            </template>

            <template #content>
                <div class="mb-4">
                    <Link :href="route('suppliers.create')">
                        <Button label="Nuevo Proveedor" icon="pi pi-plus" severity="success"></Button>
                    </Link>
                </div>
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="w-full bg-gray-100 text-left text-gray-600">
                            <th class="py-2 px-4 border-b">Nombre</th>
                            <th class="py-2 px-4 border-b">Apellidos</th>
                            <th class="py-2 px-4 border-b">Email</th>
                            <th class="py-2 px-4 border-b">Teléfono</th>
                            <th class="py-2 px-4 border-b"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="supplier in suppliers" :key="supplier.id" class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b">{{ supplier.first_name }}</td>
                            <td class="py-2 px-4 border-b">{{ supplier.last_name }}</td>
                            <td class="py-2 px-4 border-b">{{ supplier.email }}</td>
                            <td class="py-2 px-4 border-b">{{ supplier.phone }}</td>
                            <td class="py-2 px-4 border-b">
                                <Button icon="pi pi-trash" severity="danger"></Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </template>
        </Card>
    </UserLayout>
</template>