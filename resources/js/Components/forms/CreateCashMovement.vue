<script setup lang="ts">
import { SuccessResponse } from '@/types';
import axios, { AxiosResponse } from 'axios';
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import InputNumber from 'primevue/inputnumber';
import Select from 'primevue/select';
import Textarea from 'primevue/textarea';
import { useToast } from 'primevue/usetoast';
import { reactive } from 'vue';

const toast = useToast();
const emit = defineEmits(['save', 'cancel'])
const form = reactive({
    type: 'entry',
    amount: null,
    description: '',
    method: 'cash',
    date: '',
    processing: false
});

const submit = () => {
    form.processing = true
    axios.post(route('api.cash-flows.store'), {
        type: form.type,
        amount: form.amount,
        method: form.method,
        description: form.description,
        date: form.date,
    })
    .then((response: AxiosResponse<SuccessResponse>) =>{
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 2000 })
        form.processing = false
        emit('save')
    })
    .catch(({ response }) => {
        console.error(response);
        toast.add({ severity: 'error', summary: 'Error', detail: response.data.message, life: 2100 });
        form.processing = false
    });
};

const types = [
    { label: 'Entrada', value: 'entry' },
    { label: 'Salida', value: 'exit' },
];

const methods = [
    { label: 'Efectivo', value: 'cash' },
    { label: 'Tarjeta', value: 'card' },
    { label: 'Transferencia', value: 'transfer' },
];
</script>
<template>
    <form @submit.prevent="submit">
        <div class="flex mb-4">
            <div class="w-1/2 mr-2">
                <label for="type" class="block">Tipo</label>
                <Select v-model="form.type" :options="types" optionLabel="label" optionValue="value" class="w-full"></Select>
            </div>
            <div class="w-1/2">
                <label for="date" class="block">Fecha</label>
                <DatePicker dateFormat="dd/mm/yy" v-model="form.date" id="date" class="w-full" required placeholder="DD/MM/YYYY"/>
            </div>
        </div>
        <div class="flex mb-4">
            <div class="w-full mr-2">
                <label for="method" class="block">Método</label>
                <Select v-model="form.method" :options="methods" optionLabel="label" optionValue="value" class="w-full"></Select>
            </div>
            <div class="w-full">
                <label for="amount" class="block">Monto</label>
                <InputNumber v-model="form.amount" id="amount" class="w-full" required :min="0" :step="0.01" showButtons placeholder="0.00"/>
            </div>
        </div>
        <div class="mb-4">
            <label for="description" class="block">Motivo</label>
            <Textarea v-model="form.description" id="description" class="w-full" rows="4" placeholder="Reposición de dinero"></Textarea>
        </div>
        <div class="mt-6">
            <Button type="submit" :disabled="form.processing" severity="success">Registrar</Button>
            <slot></slot>
        </div>
    </form>
</template>