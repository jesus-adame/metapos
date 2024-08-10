<script setup>
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import CardContainer from '@/Components/CardContainer.vue';
import ListCustomers from '@/Components/tables/ListCustomers.vue';

defineProps({
    title: {
        type: String
    },
    customers: {
        type: Array
    },
});
</script>

<template>
    <Head :title="title" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ title }}</h2>
        </template>

        <CardContainer>
            <ListCustomers></ListCustomers>
        </CardContainer>
    </UserLayout>
</template>