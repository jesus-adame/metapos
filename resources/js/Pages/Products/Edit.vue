<script setup lang="ts">
import EditProduct from '@/Components/forms/EditProduct.vue';
import UserLayout from '@/Layouts/UserLayout.vue';
import { Product } from '@/types';
import { Head } from '@inertiajs/vue3';

const { product } = defineProps<{
    product: Product | undefined
}>()
</script>

<template>

    <Head :title="product?.name" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Producto {{ product?.name }}</h2>
        </template>

        <div class="py-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <EditProduct :product="product"></EditProduct>
                </div>
            </div>
        </div>
    </UserLayout>
</template>