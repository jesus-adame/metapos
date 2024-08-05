<script setup>
import { useForm, Head, usePage } from '@inertiajs/vue3';
import CentralLayout from '@/Layouts/CentralLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const { props } = usePage();
const setting = props.setting;

const form = useForm({
    value: setting.value,
});

const submit = () => {
    form.put(route('settings.update', setting.id), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Edit Setting" />

    <CentralLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Setting</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="value" class="block">Value</label>
                                <textarea v-model="form.value" id="value" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                            </div>
                            <div class="mt-6">
                                <PrimaryButton type="submit" :disabled="form.processing">Update Setting</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </CentralLayout>
</template>
