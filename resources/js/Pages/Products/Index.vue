<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import ListInventory from '@/Components/tables/ListInventory.vue';
import CreateInventoryTransaction from '@/Components/forms/CreateInventoryTransaction.vue';
import { ref } from 'vue';
import { can } from '@/helpers';

defineProps({
    title: {
        type: String
    },
    products: {
        type: Array
    },
});

const modalCreateMovement = ref(false)

const closeModalCreate = () => {
    modalCreateMovement.value = false
}

const openModalCreate = () => {
    modalCreateMovement.value = true
}

const saved = () => {
    closeModalCreate()
    window.location.reload()
}
</script>
<template>
    <Head title="Inventarios" />

    <Dialog v-model:visible="modalCreateMovement" modal header="Nuevo movimiento" :style="{ width: '35rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <CreateInventoryTransaction @save="saved"></CreateInventoryTransaction>
    </Dialog>

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventario</h2>
        </template>

        <div class="flex mb-4 mt-6">
            <Link :href="route('inventory-transactions.index')" class="mr-2">
                <Button label="Historial de movimientos" icon="pi pi-list"></Button>
            </Link>
            <Button v-if="can('update products')" class="mr-2" label="Registrar movimiento" icon="pi pi-sort-alt" severity="info" @click="openModalCreate"></Button>
            <Link v-if="can('create purchase')" :href="route('purchases.create')">
                <Button label="Nueva compra" icon="pi pi-plus" severity="success"></Button>
            </Link>
        </div>
        <ListInventory></ListInventory>
    </UserLayout>
</template>