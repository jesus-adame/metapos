<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';

const page = usePage()
defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    _token: page.props.csrf_token,
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <h1 class="font-bold uppercase text-lg mb-2 text-gray-700">Cambio de contraseña</h1>
            <div class="mb-4 text-sm text-gray-600">
                <h3 class="font-bold">¿Olvidaste tu contraseña? No hay problema.</h3>
                <p>Simplemente dinos tu dirección de correo electrónico y te enviaremos un enlace para restablecerla.</p>
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Enviar enlace para restablecer
                    </Button>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
