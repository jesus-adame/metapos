<script lang="ts" setup>
import { onMounted, ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import axios, { AxiosError, AxiosResponse } from 'axios';
import { useToast } from 'primevue/usetoast';
import { usePage } from '@inertiajs/vue3'
import { CashRegister, ErrorResponse, Location } from '@/types';
import CashRegisterService from '@/Services/CashRegisterService';
import Button from 'primevue/button';
import { locationIcon } from '@/helpers';
import { useAuthStore } from '@/stores/AuthStore';

const page = usePage()
const toast = useToast()
const selectedCashRegister = ref<CashRegister | null>(null)
const selectedLocation = ref<Location | null>(page.props.location)
const cashRegisters = ref<CashRegister[]>([])
const cashRegisterService = new CashRegisterService
const authStore = useAuthStore()

const modalCashRegister = ref(false)

const changeCashRegister = () => {
    const url = route('api.cash-registers.select')

    axios.post(url, {
        cash_register_id: selectedCashRegister.value?.id
    })
    .then(response => {
        authStore.setCashRegister(response.data.cashRegister)
        authStore.setLocation(response.data.location)
        hideModal()
        toast.add({ severity: 'success', summary: 'Completado', detail: response.data.message, life: 1100 });
    })
}

const showModal = () => {
    modalCashRegister.value = true;
}

const hideModal = () => {
    modalCashRegister.value = false;
}

const searchCashRegisters = () => {
    cashRegisterService.search({
        location_id: selectedLocation.value?.id
    })
    .then((response: AxiosResponse) => {
        cashRegisters.value = response.data
    })
    .catch((reject: AxiosError<ErrorResponse>) => {
        const message = reject.response?.data?.message || 'Error desconocido'
        toast.add({ severity: 'error', summary: 'Error', detail: message, life: 1500 })
    })
}

onMounted(() => {
    searchCashRegisters()
})
</script>
<template>
    <Dialog v-model:visible="modalCashRegister" header="Cambiar caja" modal>
        <div class="d-block">
            <Select placeholder="Sucursal" id="location" v-model="selectedLocation" :options="authStore.locations" optionLabel="name" class="w-60 my-4" @change="searchCashRegisters"></Select>
        </div>
        <div class="d-block">
            <Select placeholder="- Elegir -" id="cashRegister" v-model="selectedCashRegister" :options="cashRegisters" optionLabel="name" class="w-60 my-4"></Select>
        </div>
        <div class="text-end">
            <Button raised label="Aplicar" @click="changeCashRegister" severity="warn"</Button>
        </div>
    </Dialog>

    <Dropdown align="right" width="48">
        <template #trigger>
            <span class="inline-flex rounded-md">
                <button type="button">
                    <div class="text-gray-700 px-3 py-1 shadow-md flex items-center justify-between w-62 cursor-pointer rounded-md">
                        <div class="flex gap-1 items-center">
                            <div class="py-1 px-2 bg-gray-300 rounded-full text-gray-500">
                                <i :class="locationIcon(authStore.location)"></i>
                            </div>
                            <div class="ml-2 text-left font-medium">
                                <p>{{ authStore.location?.name }}</p>
                                <span class="rounded-lg bg-yellow-500 px-2 text-xs text-yellow-100">{{ authStore.cashRegister?.name }}</span>
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
            <DropdownLink :href="route('logout', { _token: authStore.csrf_token })" method="post" as="button">
                Cerrar sesión
            </DropdownLink>
        </template>
    </Dropdown>
</template>