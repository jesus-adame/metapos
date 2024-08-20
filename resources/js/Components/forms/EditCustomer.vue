<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import { Customer } from '@/types';

const { customer } = defineProps<{
    customer: Customer
}>()
const emit = defineEmits(['save'])
const form = useForm({
    firstname: customer.lastname,
    lastname: customer.lastname,
    email: customer.email,
    phone: customer.phone,
    address: customer.address,
});

const submit = () => {
    form.post(route('api.customers.update', { customer: customer.id }), {
        onSuccess: () => {
            emit('save')
            form.reset()
        }
    });
};
</script>

<template>
    <form @submit.prevent="submit">
        <div>
            <label for="firstname" class="block">Nombre</label>
            <InputText class="w-full" v-model="form.firstname"></InputText>
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
        <div class="mt-4">
            <Button type="submit" :disabled="form.processing">Registrar</Button>
        </div>
    </form>
</template>
