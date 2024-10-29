<script lang="ts" setup>
import InventoryValues from '@/Components/charts/InventoryValues.vue';
import UtilityData from '@/Components/charts/UtilityData.vue';
import WeekSales from '@/Components/charts/WeekSales.vue';
import { PERMISSIONS } from '@/consts/role_permissions';
import { can } from '@/helpers';
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';

</script>
<template>
    <Head title="Reportes" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reportes</h2>
        </template>

        <div v-if="can(PERMISSIONS.VIEW_FINANCES)" class="grid md:grid-cols-2 gap-2 py-4 my-6 mx-6">
            <UtilityData></UtilityData>
            <WeekSales></WeekSales>
            <div>
                <InventoryValues></InventoryValues>
            </div>
        </div>
    </UserLayout>
</template>