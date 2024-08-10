<script setup>
import { useForm } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

const emit = defineEmits(['save'])
const form = useForm({
    first_name: null,
    last_name: null,
    email: null,
    phone: null,
    address: null,
});

const submit = () => {
    form.post(route('api.suppliers.store'), {
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
            <label for="first_name" class="block">Nombre</label>
            <InputText class="w-full" v-model="form.first_name"></InputText>
        </div>
        <div>
            <label for="last_name" class="block">Apellido</label>
            <InputText class="w-full" v-model="form.last_name"></InputText>
        </div>
        <div>
            <label for="email" class="block">Email</label>
            <InputText class="w-full" v-model="form.email" type="email"></InputText>
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
