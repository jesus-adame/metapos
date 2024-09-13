<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import ListExpenses from '@/Components/tables/ListExpenses.vue';

</script>

<template>
    <Head title="Expenses" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Expenses</h2>
        </template>
        <div class="my-4">
            <Link :href="route('sales.index')">
                <Button class="mb-4" label="Ventas" severity="info" icon="pi pi-money-bill"></Button>
            </Link>
            <ListExpenses></ListExpenses>
        </div>
    </UserLayout>
</template>