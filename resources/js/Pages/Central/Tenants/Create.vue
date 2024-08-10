<script setup>
import CentralLayout from '@/Layouts/CentralLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Card from 'primevue/card';
import InputText from 'primevue/inputtext';
import { ref } from 'vue';

const form = ref({
    company: null,
    domain: null
})

function submit() {
    router.post(route('central.tenants.store'), form.value)
}
</script>

<template>
    <Head title="Registrar entorno" />

    <CentralLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Entorno</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <template #title>Registrar entorno</template>
                    <template #content>
                        <form @submit.prevent="submit">
                            <label for="company">Compañía:</label>
                            <InputText class="w-full" id="company" v-model="form.company" />
                            <label for="domain">Dominio:</label>
                            <InputText class="w-full" id="domain" v-model="form.domain" />
                            <Button type="submit" class="mt-4">Crear tenant</Button>
                        </form>
                    </template>
                </Card>
            </div>
        </div>
    </CentralLayout>
</template>
