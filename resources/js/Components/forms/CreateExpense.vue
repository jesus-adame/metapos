<script setup lang="ts">
import Button from 'primevue/button';
import { onMounted, reactive } from 'vue';
import axios, { AxiosResponse } from 'axios';
import { useToast } from 'primevue/usetoast';
import { useExpenseStore } from '@/stores/ExpenseStore';
import { useExpenseCategoryStore } from '@/stores/ExpenseCategoryStore';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';

const toast = useToast();
const expenseService = useExpenseStore()
const expenseCategoryStore = useExpenseCategoryStore()
const emit = defineEmits(['save'])
const form = reactive({
    amount: null,
    category_id: null,
    expense_date: null,
    processing: false,
});

const submit = () => {
    form.processing = true
    axios.post(route('api.expenses.store'), form)
    .then((response: AxiosResponse) => {
        form.processing = false
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 2000})
        expenseService.pushItem(response.data.data)
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
    <div class="grid gap-2">
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
            <Button raised @click="submit" :disabled="form.processing" label="Registrar" severity="success"></Button>
        </div>
    </div>
</template>
