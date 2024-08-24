<script lang="ts" setup>
import CustomerService from '@/Services/CustomerService';
import { Customer, Sale } from '@/types';
import { AutoCompleteCompleteEvent } from 'primevue/autocomplete';
import { reactive, ref } from 'vue';
import CreateCustomer from './CreateCustomer.vue';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import axios, { AxiosResponse } from 'axios';

const props = defineProps<{
    sale: Sale
}>()

const emit = defineEmits(['save'])

const customerService = new CustomerService();
const filteredCustomers = ref<Customer[]>([]);
const selectedCustomer = ref<Customer | null>(null);
const modalCreateCustomer = ref(false)
const form = reactive<{
    customer: any,
    customer_id: number | null
}>({
    customer: null,
    customer_id: null
})

const addCustomer = () => {
    axios.post(route('api.sales.set-customer', {sale: props.sale?.id}), form)
    .then((response: AxiosResponse) => {
        console.log(response.data.message);
        emit('save')
        clearCustomer()
    })
}

const setCustomer = () => {
    form.customer_id = selectedCustomer.value?.id || null
}

const searchCustomer = (event: AutoCompleteCompleteEvent) => {
    setTimeout(() => {
        customerService.findByCode(event.query.trim())
            .then((response) => {
                filteredCustomers.value = [...response.data]; // Asegúrate de que response.data es un array
            })
            .catch((error) => {
                console.error('Error fetching customers', error);
            });
    }, 250);
}

const showModalCreateCustomer = () => {
    modalCreateCustomer.value = true
}

const hideModalCreateCustomer = () => {
    modalCreateCustomer.value = false
}

const clearCustomer = () => {
    selectedCustomer.value = null
}
</script>
<template>
    <Dialog v-model:visible="modalCreateCustomer" modal header="Registrar cliente" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <CreateCustomer class="mt-4" @save="hideModalCreateCustomer"></CreateCustomer>
    </Dialog>

    <div class="flex pt-2">
        <AutoComplete v-model="selectedCustomer" optionLabel="name" :suggestions="filteredCustomers"
            @complete="searchCustomer"
            @change="setCustomer"
            class="w-full"
            inputClass="w-full"
            placeholder="Buscar cliente">
            <template #option="slot">
                <div class="flex align-options-center">
                    <div>
                        {{ slot.option.name }} {{ slot.option.lastname }} | {{ slot.option.phone }}
                    </div>
                </div>
            </template>
        </AutoComplete>
        <Button v-tooltip.top="'Nuevo cliente'" icon="pi pi-plus" class="ml-2" severity="success" raised @click="showModalCreateCustomer"></Button>
    </div>
    <div v-if="selectedCustomer?.id" class="flex justify-between items-center my-2">
        <Tag :value="selectedCustomer?.name + ' ' + selectedCustomer?.lastname + ' ' + selectedCustomer?.phone"></Tag>
        <Button severity="danger" icon="pi pi-times" text @click="clearCustomer"></Button>
    </div>
    <form @submit.prevent="addCustomer()">
        <div class="flex justify-end mt-4">
            <Button v-if="selectedCustomer?.id" type="submit" label="Aplicar" severity="success"></Button>
        </div>
    </form>
</template>