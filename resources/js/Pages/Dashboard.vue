<script setup lang="ts">
import ResumeData from '@/Components/blocks/ResumeData.vue';
import SaleCategories from '@/Components/charts/SaleCategories.vue';
import YearSales from '@/Components/charts/YearSales.vue';
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage()
</script>
<template>
    <Head title="Panel" />
    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel</h2>
        </template>

        <div>
            <ResumeData></ResumeData>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg my-4">
            <div class="p-6 text-gray-900 text-lg">
                Buen día {{ page.props.auth.user.name }}
            </div>
        </div>
        <div class="flex py-4 my-6">
            <div class="w-1/2">
                <YearSales></YearSales>
            </div>
            <div class="w-1/2">
                <SaleCategories></SaleCategories>
            </div>
        </div>
    </UserLayout>
</template>
