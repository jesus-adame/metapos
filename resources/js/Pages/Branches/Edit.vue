<script setup>
import { useForm, Head, usePage } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';

const { props } = usePage();
const branch = props.branch;

const form = useForm({
    name: branch.name,
    address: branch.address,
    type: branch.type,
});

const submit = () => {
    form.put(route('branches.update', branch.id));
};
</script>

<template>
    <Head title="Edit Branch" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Branch</h2>
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
                            <Button type="submit" :disabled="form.processing">Update Branch</Button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
