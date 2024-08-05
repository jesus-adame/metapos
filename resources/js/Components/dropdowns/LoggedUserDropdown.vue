<script lang="ts" setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import axios from 'axios';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import { useToast } from 'primevue/usetoast';
import { router, usePage } from '@inertiajs/vue3'
import { CashRegister } from '@/types';

const selectedCashRegister = ref<CashRegister | null>(null)
const toast = useToast()
const page = usePage()

const modalCashRegister = ref(false)

const changeCashRegister = () => {
    const url = route('cashRegisters.select')

    axios.post(url, {cashRegisterId: selectedCashRegister.value?.id})
    .then(response => {
        hideModal()
        toast.add({ severity: 'success', summary: 'Completado', detail: 'Se ha cambiado la caja', life: 1100 });
    })
}

const showModal = () => {
    modalCashRegister.value = true;
}

const hideModal = () => {
    modalCashRegister.value = false;
    router.visit(route('sales.create'));
}
</script>
<template>
    <Dialog v-model:visible="modalCashRegister" header="Cambiar caja" modal>
        <Select v-model="selectedCashRegister" :options="page.props.cashRegisters" optionLabel="name" class="w-56 my-4"></Select>

        <div class="text-end">
            <Button label="Aplicar" @click="changeCashRegister"></Button>
        </div>
    </Dialog>

    <Dropdown align="right" width="48">
        <template #trigger>
            <span class="inline-flex rounded-md">
                <button type="button">
                    <div class="text-gray-700 px-4 py-2 shadow-md flex items-center justify-between w-56 cursor-pointer rounded-md">
                        <div class="flex items-center">
                            <div class="py-2 px-3 bg-gray-300 rounded-full mr-1 text-gray-500">
                                <i class="pi pi-building"></i>
                            </div>
                            <div class="ml-2 text-left">
                                <p>{{ page.props.branch?.name }}</p>
                                <span class="rounded-lg bg-green-500 px-2 text-sm text-green-100">{{ page.props.cashRegister?.name }}</span>
                            </div>
                        </div>
                        <svg
                            class="ms-2 -me-0.5 h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                </button>
            </span>
        </template>

        <template #content>
            <DropdownLink :href="route('profile.edit')"> Perfil </DropdownLink>
            <div @click="showModal" class="cursor-pointer block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                Cambiar de caja
            </div>
            <DropdownLink :href="route('logout')" method="post" as="button">
                Cerrar sesión
            </DropdownLink>
        </template>
    </Dropdown>
</template>