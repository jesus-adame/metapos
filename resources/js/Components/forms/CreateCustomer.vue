<script setup lang="ts">
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import { reactive } from 'vue';
import axios, { AxiosResponse } from 'axios';
import { useToast } from 'primevue/usetoast';
import { useCustomerStore } from '@/stores/CustomerStore';

const toast = useToast();
const customerStore = useCustomerStore()
const emit = defineEmits(['save'])
const form = reactive({
    name: null,
    lastname: null,
    email: null,
    phone: null,
    address: null,
    processing: false
});

const submit = () => {
    form.processing = true
    axios.post(route('api.customers.store'), form)
    .then((response: AxiosResponse) => {
        form.processing = false
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 2000})
        customerStore.pushItem(response.data.customer)
        emit('save')
    })
    .catch(({response}) => {
        form.processing = false
        toast.add({ summary: 'Error', detail: response.data.message, severity: 'error', life: 2000})
    })
};
</script>
<template>
    <form @submit.prevent="submit">
        <div class="flex">
            <div class="w-full mr-2">
                <label for="name" class="block">Nombre</label>
                <InputText name="name" class="w-full" v-model="form.name"></InputText>
            </div>
            <div class="w-full">
                <label for="lastname" class="block">Apellidos</label>
                <InputText name="lastname" class="w-full" v-model="form.lastname"></InputText>
            </div>
        </div>
        <div>
            <label for="email" class="block">Email</label>
            <InputText type="email" class="w-full" v-model="form.email"></InputText>
        </div>
        <div>
            <label for="phone" class="block">Telefono</label>
            <InputText name="phone" class="w-full" v-model="form.phone"></InputText>
        </div>
        <div>
            <label for="address" class="block">Dirección</label>
            <InputText name="address" class="w-full" v-model="form.address"></InputText>
        </div>
        <div class="mt-4 flex justify-end">
            <Button type="submit" :disabled="form.processing" label="Registrar" severity="success"></Button>
        </div>
    </form>
</template>
