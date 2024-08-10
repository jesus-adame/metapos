<script setup>
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import CreateCustomer from '@/Components/forms/CreateCustomer.vue';
</script>

<template>
    <Head title="Registrar Cliente" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Cliente</h2>
        </template>

        <div class="py-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <CreateCustomer></CreateCustomer>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
