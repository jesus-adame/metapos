<script setup lang="ts">
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import axios, { AxiosResponse } from 'axios';
import { reactive } from 'vue';
import { Supplier } from '@/types';

const toast = useToast();
const props = defineProps<{
    supplier: Supplier | null
}>();
const emit = defineEmits(['save'])
const form = reactive({
    name: props.supplier?.name,
    lastname: props.supplier?.lastname,
    email: props.supplier?.email,
    phone: props.supplier?.phone,
    address: props.supplier?.address,
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
            <Button raised type="submit" :disabled="form.processing" label="Actualizar" severity="warn"></Button>
        </div>
    </form>
</template>
