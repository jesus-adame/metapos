<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    branch: '',
    address: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div
            class="w-full sm:max-w-lg mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
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
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                    <div class="w-1/2">
                        <InputLabel for="lastname" value="Apellidos" />
                        <InputText
                            id="lastname"
                            class="mt-1 block w-full"
                            v-model="form.lastname"
                            autocomplete="lastname"
                        />
                        <InputError class="mt-2" :message="form.errors.lastname" />
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

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="branch" value="Sucursal" />
                    <InputText
                        id="branch"
                        class="mt-1 block w-full"
                        v-model="form.branch"
                    />

                    <InputError class="mt-2" :message="form.errors.branch" />
                </div>

                <div class="mt-4">
                    <InputLabel for="address" value="Dirección" />
                    <InputText
                        id="address"
                        class="mt-1 block w-full"
                        v-model="form.address"
                    />

                    <InputError class="mt-2" :message="form.errors.address" />
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

                        <InputError class="mt-2" :message="form.errors.password" />
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

                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
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
