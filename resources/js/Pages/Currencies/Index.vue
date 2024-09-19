<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import ListCurrencies from '@/Components/tables/ListCurrencies.vue';

</script>

<template>
    <Head title="Monedas" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Monedas</h2>
        </template>
        <div class="my-4">
            <ListCurrencies></ListCurrencies>
        </div>
    </UserLayout>
</template>