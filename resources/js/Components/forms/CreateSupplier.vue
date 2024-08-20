<script setup lang="ts">
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import axios, { AxiosResponse } from 'axios';
import { reactive } from 'vue';

const toast = useToast();
//const customerStore = useCustomerStore()
const emit = defineEmits(['save'])
const form = reactive({
    firstname: null,
    lastname: null,
    email: null,
    phone: null,
    address: null,
    processing: false,
});

const submit = () => {
    form.processing = true
    axios.post(route('api.suppliers.store'), form)
    .then((response: AxiosResponse) => {
        form.processing = false
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 2000})
        emit('save')
    })
    .catch(({response}) => {
        toast.add({ summary: 'Error', detail: response.data.message, severity: 'error', life: 2000})
    })
};
</script>

<template>
    <form @submit.prevent="submit">
        <div class="flex">
            <div class="w-full mr-2">
                <label for="firstname" class="block">Nombre</label>
                <InputText name="name" class="w-full" v-model="form.firstname"></InputText>
            </div>
            <div class="w-full">
                <label for="lastname" class="block">Apellido</label>
                <InputText name="lastname" class="w-full" v-model="form.lastname"></InputText>
            </div>
        </div>
        <div>
            <label for="email" class="block">Email</label>
            <InputText class="w-full" v-model="form.email" type="email"></InputText>
        </div>
        <div>
            <label for="phone" class="block">Telefono</label>
            <InputText name="phone" class="w-full" v-model="form.phone"></InputText>
        </div>
        <div>
            <label for="address" class="block">Dirección</label>
            <InputText name="address" class="w-full" v-model="form.address"></InputText>
        </div>
        <div class="mt-4">
            <Button type="submit" :disabled="form.processing" severity="success">Registrar</Button>
        </div>
    </form>
</template>
