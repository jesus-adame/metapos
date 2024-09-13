<script setup lang="ts">
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import { ExpenseCategory } from '@/types';
import { reactive } from 'vue';
import axios, { AxiosResponse } from 'axios';
import { useToast } from 'primevue/usetoast';

const { category } = defineProps<{
    category: ExpenseCategory | null
}>()
const toast = useToast();
const emit = defineEmits(['save'])
const form = reactive({
    name: category?.name,
    description: category?.description,
    _method: 'put',
    processing: false,
});

const submit = () => {
    form.processing = true
    axios.post(route('api.expense_categories.update', { category: category?.id }), form)
    .then((response: AxiosResponse) => {
        form.processing = false
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 2000})
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
        <div>
            <label for="name" class="block">Nombre</label>
            <InputText class="w-full" v-model="form.name"></InputText>
        </div>
        <div>
            <label for="description" class="block">Descripción</label>
            <InputText class="w-full" v-model="form.description"></InputText>
        </div>
        <div class="mt-4 flex justify-end">
            <Button type="submit" :disabled="form.processing" severity="warn" label="Actualizar"></Button>
        </div>
    </form>
</template>
