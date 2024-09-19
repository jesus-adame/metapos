<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import ListQuotation from '@/Components/tables/ListQuotation.vue';
import DatePicker from 'primevue/datepicker';
import { ref, watch } from 'vue';
import { useQuoteStore } from '@/stores/QuoteStore';
import { formatMoneyNumber } from '@/helpers';

const dates = ref([])
const selectedStatus = ref<string | null>(null)
const quoteStore = useQuoteStore()

watch(dates, (dates) => {
    quoteStore.setDates(dates)
    quoteStore.fetchItems()
})

watch(selectedStatus, (status) => {
    quoteStore.setStatus(status)
    quoteStore.fetchItems()
})

const clearStatus = () => {
    selectedStatus.value = null
}
</script>
<template>
    <Head title="Cotizaciones" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cotizaciones</h2>
        </template>

        <div class="mt-4 mb-4 flex justify-between">
            <div>
                <Link :href="route('sales.create')">
                    <Button raised label="Nueva cotización" severity="success" icon="pi pi-plus"></Button>
                </Link>
                <Link :href="route('customers.index')" class="ml-2">
                    <Button raised label="Clientes" severity="info" icon="pi pi-users"></Button>
                </Link>
            </div>
            <div class="flex gap-2 items-center">
                <DatePicker v-model="dates" selectionMode="range" :manualInput="false" dateFormat="dd/mm/yy" showButtonBar placeholder="dd/mm/yyyy - dd/mm/yyyy" />
                <div class="flex gap-2">
                    <Select class="w-30" v-model="selectedStatus" :options="quoteStore.statusList || []" optionLabel="label" optionValue="value" placeholder="Estatus"></Select>
                    <Button v-if="selectedStatus" @click="clearStatus" severity="danger" icon="pi pi-times" text></Button>
                </div>
            </div>
        </div>
        <ListQuotation></ListQuotation>
    </UserLayout>
</template>