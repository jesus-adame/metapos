<script setup lang="ts">
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import { Customer } from '@/types';
import { reactive } from 'vue';
import axios, { AxiosResponse } from 'axios';
import { useToast } from 'primevue/usetoast';

const { customer } = defineProps<{
    customer: Customer | null
}>()
const toast = useToast();
const emit = defineEmits(['save'])
const form = reactive({
    name: customer?.name,
    lastname: customer?.lastname,
    email: customer?.email,
    phone: customer?.phone,
    address: customer?.address,
    _method: 'put',
    processing: false,
});

const submit = () => {
    form.processing = true
    axios.post(route('api.customers.update', { customer: customer?.id }), form)
    .then((response: AxiosResponse) => {
        form.processing = false
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 2000})
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
        <div>
            <label for="name" class="block">Nombre</label>
            <InputText class="w-full" v-model="form.name"></InputText>
        </div>
        <div>
            <label for="lastname" class="block">Apellido</label>
            <InputText class="w-full" v-model="form.lastname"></InputText>
        </div>
        <div>
            <label for="email" class="block">Email</label>
            <InputText type="email" class="w-full" v-model="form.email"></InputText>
        </div>
        <div>
            <label for="phone" class="block">Telefono</label>
            <InputText class="w-full" v-model="form.phone"></InputText>
        </div>
        <div>
            <label for="address" class="block">Dirección</label>
            <InputText class="w-full" v-model="form.address"></InputText>
        </div>
        <div class="mt-4 flex justify-end">
            <Button type="submit" :disabled="form.processing" severity="warn" label="Actualizar"></Button>
        </div>
    </form>
</template>
