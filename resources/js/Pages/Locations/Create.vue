<script setup>
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import CreateLocation from '@/Components/forms/CreateLocation.vue';
</script>

<template>
    <Head title="Nueva ubicación" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar sucursal</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <CreateLocation></CreateLocation>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
