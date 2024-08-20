<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { reactive } from 'vue';
import { useToast } from 'primevue/usetoast';
import axios, { AxiosError, AxiosResponse } from 'axios';
import { ErrorResponse, SuccessResponse } from '@/types';

const page = usePage();
const toast = useToast();
const form = reactive({
    name: '',
    lastname: '',
    email: '',
    password: '',
    password_confirmation: '',
    location: '',
    address: '',
    errors: {},
    processing: false,
});

const submit = () => {
    form.processing = true;
    axios.post(route('register'), {
        name: form.name,
        lastname: form.lastname,
        email: form.email,
        password: form.password,
        password_confirmation: form.password_confirmation,
        location: form.location,
        address: form.address,
        _token: page.props.csrf_token,
    })
    .then((response: AxiosResponse<SuccessResponse>) => {
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 1200 })
        // Redirigir o actualizar la vista
        router.visit(route('home'));
    })
    .catch((error: AxiosError<ErrorResponse>) => {
        toast.add({ summary: 'Error', detail: error.response?.data.message, severity: 'error', life: 1500 })
    })
    .then(() => {
        form.processing = false;
    })
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="w-full sm:max-w-lg mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="w-full flex justify-center">
                <Link href="/">
                    <ApplicationLogo class="w-20 h-20 fill-current text-gray-600" />
                    <h1 class="font-bold text-xl text-gray-600 mt-3 mb-1">META POS</h1>
                </Link>
            </div>
            <h1 class="font-bold uppercase text-lg mb-2">Ingresa tus datos</h1>
            <form @submit.prevent="submit">
                <div class="flex">
                    <div class="mr-2 w-1/2">
                        <InputLabel for="name" value="Nombre" />
                        <InputText
                            id="name"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            autocomplete="name"
                        />
                    </div>
                    <div class="w-1/2">
                        <InputLabel for="lastname" value="Apellidos" />
                        <InputText
                            id="lastname"
                            class="mt-1 block w-full"
                            v-model="form.lastname"
                            autocomplete="lastname"
                        />
                    </div>
                </div>
                <div class="mt-4">
                    <InputLabel for="email" value="Email" />
                    <InputText
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        autocomplete="username"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="location" value="Sucursal" />
                    <InputText
                        id="location"
                        class="mt-1 block w-full"
                        v-model="form.location"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="address" value="Dirección" />
                    <InputText
                        id="address"
                        class="mt-1 block w-full"
                        v-model="form.address"
                    />
                </div>
                <div class="flex">
                    <div class="mt-4 w-1/2 mr-2">
                        <InputLabel for="password" value="Contraseña" />
                        <InputText
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            autocomplete="new-password"
                        />
                    </div>
                    <div class="mt-4 w-1/2">
                        <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                        <InputText
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password_confirmation"
                            autocomplete="new-password"
                        />
                    </div>
                </div>
                <div class="flex items-center justify-end mt-6">
                    <Link
                        :href="route('login')"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        ¿Ya está registrado?
                    </Link>
                    <Button class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" label="Registrar" type="submit"></Button>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
