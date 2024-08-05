<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import ListCashCuts from '@/Components/tables/ListCashCuts.vue';
</script>

<template>
    <Head title="Cortes de caja" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cortes de caja</h2>
        </template>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
            <ListCashCuts></ListCashCuts>
        </div>
    </UserLayout>
</template>
