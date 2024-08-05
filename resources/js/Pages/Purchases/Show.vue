<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String
    },
    purchase: {
        type: Object
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ title }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="mb-4">
                            <p>
                                <strong>Venta</strong> {{  purchase.id }}
                            </p>
                            <p>
                                <strong>Proveedor</strong> {{ purchase.supplier.first_name }} {{ purchase.supplier.last_name }}
                            </p>
                        </div>
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="w-full bg-gray-100 text-left text-gray-600">
                                    <th class="py-2 px-4 border-b">Producto</th>
                                    <th class="py-2 px-4 border-b">Precio</th>
                                    <th class="py-2 px-4 border-b">Cantidad</th>
                                    <th class="py-2 px-4 border-b">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product in purchase.products" :key="purchase.id" class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border-b">
                                        {{ product.name }}
                                    </td>
                                    <td class="py-2 px-4 border-b">${{ product.pivot.price }}</td>
                                    <td class="py-2 px-4 border-b">{{ product.pivot.quantity }}</td>
                                    <td class="py-2 px-4 border-b">${{ parseFloat(product.pivot.price * product.pivot.quantity).toFixed(2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>