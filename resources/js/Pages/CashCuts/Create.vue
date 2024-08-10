<script setup>
import { useForm, Head, usePage } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const page = usePage()
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

        <div class="py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label for="cut_date" class="block">Cut Date</label>
                            <input type="date" v-model="form.cut_date" id="cut_date" required class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>
                        <div class="mt-6">
                            <PrimaryButton type="submit" :disabled="form.processing">Record Cash Cut</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
