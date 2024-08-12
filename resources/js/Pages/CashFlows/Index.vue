<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import ListCashFlows from '@/Components/tables/ListCashFlows.vue';
import ListCashCuts from '@/Components/tables/ListCashCuts.vue';
import CashResume from '@/Components/blocks/CashResume.vue';

const page = usePage()
</script>

<template>
    <Head title="Caja" />
    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Caja {{ page.props.cashRegister.name }}</h2>
        </template>
        <div class="mb-4 mt-4">
            <Link :href="route('sales.create')">
                <Button label="Vender" icon="pi pi-shopping-cart" class="ml-2" severity="success"></Button>
            </Link>
        </div>
        <div class="">
            <CashResume></CashResume>
        </div>
        <div class="my-4">
            <h3 class="text-xl font-bold my-4">Cortes de Caja</h3>
            <ListCashCuts></ListCashCuts>
        </div>
        <div>
            <h3 class="text-xl font-bold my-4">Movimientos</h3>
            <ListCashFlows/>
        </div>
    </UserLayout>
</template>
