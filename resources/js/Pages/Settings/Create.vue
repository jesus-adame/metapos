<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';

const form = useForm({
    key: '',
    label: '',
    value: '',
});

const submit = () => {
    form.post(route('settings.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Create Setting" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Setting</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="key" class="block">Clave</label>
                                <input type="text" v-model="form.key" id="key" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>
                            <div class="mb-4">
                                <label for="label" class="block">Nombre</label>
                                <input type="text" v-model="form.label" id="label" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>
                            <div class="mb-4">
                                <label for="value" class="block">Valor</label>
                                <textarea v-model="form.value" id="value" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                            </div>
                            <div class="mt-6">
                                <Button type="submit" :disabled="form.processing">Create Setting</Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
