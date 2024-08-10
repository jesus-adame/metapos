<script setup lang="ts">
import ProductService from '@/Services/ProductService';
import { ErrorResponse, Product } from '@/types';
import axios, { AxiosError, AxiosResponse } from 'axios';
import Button from 'primevue/button';
import Select from 'primevue/select';
import Textarea from 'primevue/textarea';
import { useToast } from 'primevue/usetoast';
import { onMounted, reactive, ref } from 'vue';

const toast = useToast();
const products = ref<Product[]>([]);
const productService = new ProductService();
const emit = defineEmits(['save', 'cancel'])

const form = reactive({
    product_id: '',
    type: 'entry',
    quantity: null,
    transaction_date: '',
    description: '',
    processing: false
});

const submit = () => {
    form.processing = true

    axios.post(route('api.inventory-transactions.store'), form)
    .then((response: AxiosResponse) => {
        form.processing = false
        toast.add({ severity: 'success', summary: 'Correcto', detail: response.data.message, life: 1500 })
        emit('save')
    })
    .catch((reject: AxiosError<ErrorResponse>) => {
        form.processing = false
        const message = reject.response?.data?.message || 'Error desconocido'
        toast.add({ severity: 'error', summary: 'Error', detail: message, life: 1500 })
    })
};

onMounted(() => {
    productService.fetchAll()
    .then((response: AxiosResponse) => {
        const paginate = response.data
        products.value = paginate.data

    })
})

const types = ref([
    { label: 'Entrada', value: 'entry' },
    { label: 'Salida', value: 'exit' },
])
</script>
<template>
    <form @submit.prevent="submit">
        <div class="my-4">
            <label for="type" class="block">Tipo</label>
            <Select v-model="form.type" :options="types" optionLabel="label" optionValue="value" class="w-full"></Select>
        </div>
        <div class="mb-4">
            <label for="product_id" class="block">Producto</label>
            <Select v-model="form.product_id" :options="products" optionLabel="name" optionValue="id" class="w-full"></Select>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block">Cantidad</label>
            <InputNumber v-model="form.quantity" :min="0" :step="1" class="w-full" showButtons placeholder="0"></InputNumber>
        </div>
        <div class="mb-4">
            <label for="transaction_date" class="block">Fecha de transacción</label>
            <input type="date" v-model="form.transaction_date" id="transaction_date" class="w-full">
        </div>
        <div class="mb-4">
            <label for="description" class="block">Descripción</label>
            <Textarea v-model="form.description" id="description" class="w-full" rows="4"></Textarea>
        </div>
        <div class="mt-6">
            <Button type="submit" :disabled="form.processing" label="Registrar"></Button>
        </div>
    </form>
</template>