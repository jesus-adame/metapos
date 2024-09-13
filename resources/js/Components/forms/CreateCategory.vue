<script setup lang="ts">
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import { reactive } from 'vue';
import axios, { AxiosResponse } from 'axios';
import { useToast } from 'primevue/usetoast';
import { useCategoryStore } from '@/stores/CategoryStore';

const toast = useToast();
const categoryStore = useCategoryStore()
const emit = defineEmits(['save'])
const form = reactive({
    name: null,
    description: null,
    processing: false
});

const submit = () => {
    form.processing = true
    axios.post(route('api.categories.store'), form)
    .then((response: AxiosResponse) => {
        form.processing = false
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 2000})
        categoryStore.pushItem(response.data.category)
        emit('save')
    })
    .catch(({response}) => {
        form.processing = false
        toast.add({ summary: 'Error', detail: response.data.message, severity: 'error', life: 2000})
    })
};
</script>
<template>
    <form @submit.prevent="submit">
        <div class="flex">
            <div class="w-full mr-2">
                <label for="name" class="block">Nombre</label>
                <InputText name="name" class="w-full" v-model="form.name"></InputText>
            </div>
            <div class="w-full">
                <label for="description" class="block">Descripción</label>
                <InputText name="description" class="w-full" v-model="form.description"></InputText>
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <Button type="submit" :disabled="form.processing" label="Registrar" severity="success"></Button>
        </div>
    </form>
</template>
