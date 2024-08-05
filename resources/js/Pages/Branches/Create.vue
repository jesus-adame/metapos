<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Branch</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="name" class="block">Name</label>
                                <input type="text" v-model="form.name" id="name" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                            </div>
                            <div class="mb-4">
                                <label for="address" class="block">Address</label>
                                <input type="text" v-model="form.address" id="address" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                            </div>
                            <div class="mb-4">
                                <label for="type" class="block">Type</label>
                                <select v-model="form.type" id="type" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="branch">Branch</option>
                                    <option value="warehouse">Warehouse</option>
                                </select>
                            </div>
                            <PrimaryButton type="submit" :disabled="form.processing">Create Branch</PrimaryButton>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
