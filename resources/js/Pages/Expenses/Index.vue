<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import ListExpenses from '@/Components/tables/ListExpenses.vue';
import ListExpenseCategories from '@/Components/tables/ListExpenseCategories.vue';

</script>

<template>

    <Head title="Expenses" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Expenses</h2>
        </template>
        <div class="my-4">
            <ListExpenses></ListExpenses>
        </div>

        <h3 class="text uppercase font-bold">Categorías de gastos</h3>
        <div class="my-4">
            <ListExpenseCategories></ListExpenseCategories>
        </div>
    </UserLayout>
</template>