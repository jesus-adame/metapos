<script setup>
import { usePage, Head } from '@inertiajs/vue3';
import CentralLayout from '@/Layouts/CentralLayout.vue';

const { props } = usePage();
const settings = props.settings;
</script>

<template>
    <Head title="Settings" />

    <CentralLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Settings</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="border px-4 py-2">Key</th>
                                    <th class="border px-4 py-2">Value</th>
                                    <th class="border px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="setting in settings" :key="setting.id">
                                    <td class="border px-4 py-2">{{ setting.key }}</td>
                                    <td class="border px-4 py-2">{{ setting.value }}</td>
                                    <td class="border px-4 py-2">
                                        <inertia-link :href="route('central.settings.edit', setting.id)" class="text-indigo-600 hover:text-indigo-900">Edit</inertia-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </CentralLayout>
</template>
