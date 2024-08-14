<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import CreateBranch from '@/Components/forms/CreateBranch.vue';

const form = useForm({
    name: '',
    address: '',
    type: 'branch', // Default to 'branch'
});

const submit = () => {
    form.post(route('branches.store'));
};
</script>

<template>
    <Head title="Create Branch" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear sucursal</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <CreateBranch></CreateBranch>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
