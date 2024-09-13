<script setup lang="ts">
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import { Expense } from '@/types';
import { onMounted, reactive } from 'vue';
import axios, { AxiosResponse } from 'axios';
import { useToast } from 'primevue/usetoast';
import DatePicker from 'primevue/datepicker';
import { useExpenseCategoryStore } from '@/stores/ExpenseCategoryStore';

const { expense } = defineProps<{
    expense: Expense | null
}>()
const toast = useToast();
const expenseCategoryStore = useExpenseCategoryStore()
const emit = defineEmits(['save'])
const form = reactive({
    amount: expense?.amount,
    description: expense?.description,
    expense_date: expense?.expense_date,
    category_id: expense?.expense_category.id,
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

onMounted(() => {
    expenseCategoryStore.fetchItems()
})
</script>

<template>
    <form @submit.prevent="submit">
        <div class="w-full">
            <label for="description" class="block">Descripción</label>
            <Select class="w-full" v-model="form.category_id" :options="expenseCategoryStore.categories" option-label="name" option-value="id"></Select>
        </div>
        <div class="w-full">
            <label for="amount" class="block">Monto</label>
            <InputNumber name="amount" class="w-full" v-model="form.amount" :min="0" :maxFractionDigits="2"showButtons placeholder="0.00"></InputNumber>
        </div>
        <div class="w-full">
            <label for="expense_date" class="block">Fecha de gasto</label>
            <DatePicker
                class="w-full"
                v-model="form.expense_date"
                dateFormat="dd/mm/yy"
                iconDisplay="input"
                showIcon showTime hour-format="12"
                id="expense_date"
                required
                placeholder="DD/MM/YYYY"></DatePicker>
        </div>
        <div class="mt-4 flex justify-end">
            <Button type="submit" :disabled="form.processing" severity="warn" label="Actualizar"></Button>
        </div>
    </form>
</template>
