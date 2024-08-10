<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import axios from 'axios';
import { reactive } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Checkbox from 'primevue/checkbox';

const page = usePage();

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
        });

        const token = response.data.token;
        localStorage.setItem('auth_token', token);

        // Redirigir o actualizar la vista
        router.visit(route('home'));
    } catch (error) {
        if (error.response && error.response.data.errors) {
            form.errors = error.response.data.errors;
        } else {
            console.error('Error logging in:', error);
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
                <Link href="/">
                    <ApplicationLogo class="w-20 h-20 fill-current text-gray-600" />
                    <h1 class="font-bold text-xl text-gray-600 my-3">META POS</h1>
                </Link>
            </div>
            <form @submit.prevent="submit">
                <div>
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
                    <InputLabel for="password" value="Password" />
                    <InputText
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model="form.remember" :binary="true"/>
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Forgot your password?
                    </Link>
                    <Button class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" raised label="Log in" type="submit"></Button>
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
