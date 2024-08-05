<script setup>
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Card from '@/Components/Card.vue';
import Button from 'primevue/button';
import ListInventory from '@/Components/tables/ListInventory.vue';

defineProps({
    title: {
        type: String
    },
    products: {
        type: Array
    },
});

</script>
<template>
    <Head title="Dashboard" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventario</h2>
        </template>

        <div class="flex mb-4 mt-6">
            <Link :href="route('inventory-transactions.index')" class="mr-2">
                <Button label="Movimientos" icon="pi pi-sort-alt"></Button>
            </Link>
        </div>
        <Card padding="0">
            <ListInventory></ListInventory>
        </Card>
    </UserLayout>
</template>