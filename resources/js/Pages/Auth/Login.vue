<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import axios from 'axios';
import { reactive } from 'vue';
import Checkbox from 'primevue/checkbox';
import Password from 'primevue/password';
import { useToast } from 'primevue/usetoast';

const page = usePage();
const toast = useToast();

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = reactive({
    email: '',
    password: '',
    remember: false,
    errors: {},
    processing: false,
});

const submit = async () => {
    form.processing = true;
    try {
        const response = await axios.post(route('login'), {
            email: form.email,
            password: form.password,
            remember: form.remember,
            _token: page.props.csrf_token,
        });

        const token = response.data.token;
        localStorage.setItem('auth_token', token);

        // Redirigir o actualizar la vista
        router.visit(route('home'));
    } catch (error) {
        if (error.response && error.response.data.errors) {
            toast.add({ summary: 'Error', detail: error.response?.data.message, severity: 'error', life: 1500 })
            form.errors = error.response.data.errors;
        } else {
            toast.add({ summary: 'Error de conexión', detail: error.message, severity: 'error', life: 2000 })
        }
    } finally {
        form.processing = false;
    }
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="w-full sm:max-w-md mt-6 px-6 py-5 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="w-full flex justify-center">
                <Link href="/" class="w-1/2">
                    <img src="/logos/logo-2-black.png" alt="Logo">
                </Link>
            </div>
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Correo electrónico" />
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
                    <InputLabel for="password" value="Contraseña" />
                    <Password
                        id="password"
                        class="mt-1 block"
                        :feedback="false"
                        fluid
                        v-model="form.password"
                        toggleMask
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model="form.remember" :binary="true"/>
                        <span class="ms-2 text-sm text-gray-600">Recordarme</span>
                    </label>
                </div>
                <div class="flex items-center justify-center mt-4">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        ¿Olvidaste tu contraseña?
                    </Link>
                </div>

                <div class="flex items-center justify-center mt-4">
                    <Button
                        class="w-full"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        raised label="Entrar" type="submit" severity="contrast"></Button>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
#password input {
    width: 100%;
}
</style>
