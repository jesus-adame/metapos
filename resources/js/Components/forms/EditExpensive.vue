<script setup lang="ts">
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import { Expense } from '@/types';
import { reactive } from 'vue';
import axios, { AxiosResponse } from 'axios';
import { useToast } from 'primevue/usetoast';

const { expense } = defineProps<{
    expense: Expense | null
}>()
const toast = useToast();
const emit = defineEmits(['save'])
const form = reactive({
    amount: expense?.amount,
    description: expense?.description,
    expense_date: expense?.expense_date,
    _method: 'put',
    processing: false,
});

const submit = () => {
    form.processing = true
    axios.post(route('api.expenses.update', { expense: expense?.id }), form)
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
            <label for="amount" class="block">Monto</label>
            <InputNumber class="w-full" v-model="form.amount"></InputNumber>
        </div>
        <div>
            <label for="description" class="block">Descripción</label>
            <InputText class="w-full" v-model="form.description"></InputText>
        </div>
        <div>
            <label for="expense_date" class="block">Fecha de gasto</label>
            <InputText name="expense_date" class="w-full" v-model="form.expense_date"></InputText>
        </div>
        <div class="mt-4">
            <Button type="submit" :disabled="form.processing" severity="warn" label="Actualizar"></Button>
        </div>
    </form>
</template>
