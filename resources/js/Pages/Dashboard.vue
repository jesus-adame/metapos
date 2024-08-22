<script setup lang="ts">
import ResumeData from '@/Components/blocks/ResumeData.vue';
import SaleCategories from '@/Components/charts/SaleCategories.vue';
import YearSales from '@/Components/charts/YearSales.vue';
import { can } from '@/helpers';
import UserLayout from '@/Layouts/UserLayout.vue';
import { useAuthStore } from '@/stores/AuthStore';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const store = useAuthStore()

const authName = computed(() => store.user?.name)
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
                Buen día {{ authName }}
            </div>
        </div>
        <div v-if="can('view finances')" class="grid md:grid-cols-2 py-4 my-6">
            <YearSales></YearSales>
            <SaleCategories></SaleCategories>
        </div>
    </UserLayout>
</template>
