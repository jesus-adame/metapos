<script setup>
import { useForm, Head, usePage } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';

const { props } = usePage();
const location = props.location;

const form = useForm({
    name: location.name,
    address: location.address,
    type: location.type,
});

const submit = () => {
    form.put(route('locations.update', location.id));
};
</script>

<template>
    <Head title="Edit Location" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Location</h2>
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
                                    <option value="location">Location</option>
                                    <option value="warehouse">Warehouse</option>
                                </select>
                            </div>
                            <Button type="submit" :disabled="form.processing">Update Location</Button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
