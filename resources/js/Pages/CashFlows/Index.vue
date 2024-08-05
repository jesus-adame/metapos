<script setup>
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import ListCashFlows from '@/Components/tables/ListCashFlows.vue';
</script>

<template>
    <Head title="Flujos de Caja" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Flujos de Caja</h2>
        </template>

        <div class="mb-4 mt-4">
            <Link :href="route('cash-cuts.index')">
                <Button label="Cortes de caja" icon="pi pi-sort-alt" class="ml-2"></Button>
            </Link>
        </div>
        <ListCashFlows/>
    </UserLayout>
</template>
