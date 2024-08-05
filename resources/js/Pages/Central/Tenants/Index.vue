<script setup>
import CentralLayout from '@/Layouts/CentralLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';

defineProps({
    tenants: Array
})

function sendForm(tenant) {
    router.post(route('tenants.destroy', { tenant: tenant.id }), { _method: 'delete' })
}
</script>

<template>
    <Head title="Tenants" />

    <CentralLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tenants</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Link :href="route('tenants.create')">
                    <Button label="Registrar" class="mb-4"></Button>
                </Link>
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                    <DataTable :value="tenants">
                        <Column field="id" header="Id"></Column>
                        <Column field="company" header="Compañía"></Column>
                        <Column header="Dominio">
                            <template #body="slot">
                                {{ slot.data.domains[0].domain }}
                            </template>
                        </Column>
                        <Column header="">
                            <template #body="slot">
                                <a :href="'http://' + slot.data.domains[0].domain">
                                    <Button icon="pi pi-eye" class="mb-1"></Button>
                                </a>
                                <form @submit.prevent="sendForm(slot.data)">
                                    <Button icon="pi pi-trash" severity="danger" type="submit"></Button>
                                </form>
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </CentralLayout>
</template>
