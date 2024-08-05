<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

const form = useForm({
    first_name: null,
    last_name: null,
    email: null,
    phone: null,
    address: null,
});

const submit = () => {
    form.post(route('suppliers.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Registrar Proveedor" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Proveedor</h2>
        </template>

        <div class="py-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form @submit.prevent="submit">
                        <div>
                            <label for="first_name" class="block">Nombre</label>
                            <InputText v-model="form.first_name"></InputText>
                        </div>
                        <div>
                            <label for="last_name" class="block">Apellido</label>
                            <InputText v-model="form.last_name"></InputText>
                        </div>
                        <div>
                            <label for="email" class="block">Email</label>
                            <InputText v-model="form.email" type="email"></InputText>
                        </div>
                        <div>
                            <label for="phone" class="block">Telefono</label>
                            <InputText v-model="form.phone"></InputText>
                        </div>
                        <div>
                            <label for="address" class="block">Dirección</label>
                            <InputText v-model="form.address"></InputText>
                        </div>
                        <div class="mt-4">
                            <Button type="submit" :disabled="form.processing">Registrar</Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
