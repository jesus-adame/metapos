<script setup>
import { useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import InputNumber from 'primevue/inputnumber';
import Select from 'primevue/select';
import Textarea from 'primevue/textarea';

const emit = defineEmits(['save', 'cancel'])
const form = useForm({
    type: 'entry',
    amount: null,
    description: '',
    method: 'cash',
    date: '',
});

const submit = () => {
    form.post(route('api.cash-flows.store'), {
        onSuccess: () => {
            emit('save')
            form.reset()
        },
    });
};

const types = [
    { label: 'Entrada de efectivo', value: 'entry' },
    { label: 'Salida de efectivo', value: 'exit' },
];

const methods = [
    { label: 'Efectivo', value: 'cash' },
    { label: 'Tarjeta', value: 'card' },
    { label: 'Transferencia', value: 'transfer' },
];
</script>
<template>
    <form @submit.prevent="submit">
        <div class="mb-4">
            <label for="type" class="block">Tipo</label>
            <Select v-model="form.type" :options="types" optionLabel="label" optionValue="value" class="w-full"></Select>
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
            <label for="description" class="block">Descripción</label>
            <Textarea v-model="form.description" id="description" class="w-full" rows="4"></Textarea>
        </div>
        <div class="mb-4">
            <label for="date" class="block">Fecha</label>
            <DatePicker v-model="form.date" id="date" class="w-full" required placeholder="DD/MM/YYYY"/>
        </div>
        <div class="mt-6">
            <Button type="submit" :disabled="form.processing" severity="success">Registrar</Button>
            <slot></slot>
        </div>
    </form>
</template>