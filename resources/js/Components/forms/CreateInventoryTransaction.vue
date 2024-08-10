<script setup lang="ts">
import BranchService from '@/Services/BranchService';
import ProductService from '@/Services/ProductService';
import { Branch, ErrorResponse, Product } from '@/types';
import axios, { AxiosError, AxiosResponse } from 'axios';
import Button from 'primevue/button';
import Select from 'primevue/select';
import Textarea from 'primevue/textarea';
import { useToast } from 'primevue/usetoast';
import { onMounted, reactive, ref } from 'vue';

const toast = useToast();
const products = ref<Product[]>([]);
const branches = ref<Branch[]>([]);
const productService = new ProductService();
const branchService = new BranchService();
const emit = defineEmits(['save', 'cancel'])

const form = reactive({
    product_id: '',
    type: 'entry',
    quantity: null,
    transaction_date: '',
    description: '',
    location_id: null,
    location_type: 'App\\Models\\Branch',
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

const fetchLocations = () => {
    branchService.fetchAll()
    .then((response: AxiosResponse) => {
        const paginate = response.data
        branches.value = paginate.data
    })
}

onMounted(() => {
    productService.fetchAll()
    .then((response: AxiosResponse) => {
        const paginate = response.data
        products.value = paginate.data
    })

    fetchLocations()
})

const types = ref([
    { label: 'Entrada', value: 'entry' },
    { label: 'Salida', value: 'exit' },
])

// const location_types = ref([
//     { label: 'Sucursal', value: 'App\\Models\\Branch' },
//     { label: 'Almacén', value: 'App\\Models\\Warehouse' },
// ])
</script>
<template>
    <form @submit.prevent="submit">
        <div class="my-4">
            <label for="type" class="block">Tipo</label>
            <Select v-model="form.type" :options="types" optionLabel="label" optionValue="value" class="w-full"></Select>
        </div>
        <!-- <div class="my-4">
            <label for="location_id" class="block">Tipo de ubicación</label>
            <Select v-model="form.location_type" :options="location_types" optionLabel="label" optionValue="value" class="w-full"></Select>
        </div> -->
        <div class="my-4">
            <label for="location_id" class="block">Ubicación</label>
            <Select v-model="form.location_id" :options="branches" optionLabel="name" optionValue="id" class="w-full"></Select>
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
            <Button type="submit" :disabled="form.processing" label="Registrar" severity="success"></Button>
        </div>
    </form>
</template>