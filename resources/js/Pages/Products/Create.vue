<script setup lang="ts">
import CreateProduct from '@/Components/forms/CreateProduct.vue';
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Nuevo producto" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo producto</h2>
        </template>

        <div class="py-6 w-1/2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <CreateProduct></CreateProduct>
                </div>
            </div>
        </div>
    </UserLayout>
</template>