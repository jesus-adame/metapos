<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';

const form = useForm({
    key: null,
    label: null,
    value: null,
});

const submit = () => {
    form.post(route('settings.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>
<template>
    <form @submit.prevent="submit">
        <div class="mb-4">
            <label for="key" class="block">Clave</label>
            <InputText type="text" v-model="form.key" id="key" class="w-full"></InputText>
        </div>
        <div class="mb-4">
            <label for="label" class="block">Nombre</label>
            <InputText type="text" v-model="form.label" id="label" class="w-full"></InputText>
        </div>
        <div class="mb-4">
            <label for="value" class="block">Valor</label>
            <Textarea v-model="form.value" id="value" class="w-full" :rows="5"></Textarea>
        </div>
        <div class="mt-6">
            <Button type="submit" :disabled="form.processing" label="Create Setting"></Button>
        </div>
    </form>
</template>