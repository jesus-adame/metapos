<script setup lang="ts">
import ResumeData from '@/Components/blocks/ResumeData.vue';
import InventoryValues from '@/Components/charts/InventoryValues.vue';
import WeekSales from '@/Components/charts/WeekSales.vue';
import { can } from '@/helpers';
import UserLayout from '@/Layouts/UserLayout.vue';
import { useAuthStore } from '@/stores/AuthStore';
import { Head } from '@inertiajs/vue3';

const authStore = useAuthStore()
</script>
<template>
    <Head title="Panel" />
    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel</h2>
        </template>

        <div v-if="can('view finances')">
            <ResumeData></ResumeData>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg my-4">
            <div class="p-6 text-gray-900 text-lg">
                <p>Buen día {{ authStore.user?.name }}</p>

            </div>
        </div>
        <div v-if="can('view finances')" class="grid md:grid-cols-2 gap-2 py-4 my-6">
            <WeekSales></WeekSales>
            <div>
                <InventoryValues></InventoryValues>
            </div>
        </div>
    </UserLayout>
</template>
