<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';

const form = useForm({
    cut_date: '',
});

const submit = () => {
    form.post(route('cash-cuts.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Record Cash Cut" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Record Cash Cut</h2>
        </template>

        <div class="py-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <label for="cut_date" class="block">Cut Date</label>
                        <input type="date" v-model="form.cut_date" id="cut_date" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    </div>
                    <div class="mt-6">
                        <form @submit.prevent="submit">
                            <Button type="submit" :disabled="form.processing">Record Cash Cut</Button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
