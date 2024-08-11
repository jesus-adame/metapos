<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import CreateCashMovement from '@/Components/forms/CreateCashMovement.vue';
</script>

<template>
    <Head title="Record Cash Flow" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Movimiento</h2>
        </template>

        <div class="py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <CreateCashMovement></CreateCashMovement>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
