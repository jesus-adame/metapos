<script setup lang="ts">
import CashResume from '@/Components/blocks/CashResume.vue';
import UserLayout from '@/Layouts/UserLayout.vue';
import SalesTable from '@/Shared/SalesTable.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Panel" />
    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel</h2>
        </template>

        <SalesTable></SalesTable>

        <div class="py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    ¡Has iniciado sesión!
                </div>
            </div>
        </div>
    </UserLayout>
</template>
