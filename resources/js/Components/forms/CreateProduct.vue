<script lang="ts" setup>
import { formatMoneyNumber } from '@/helpers';
import { useCategoryStore } from '@/stores/CategoryStore';
import { Product } from '@/types';
import axios, { AxiosResponse } from 'axios';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import { useToast } from 'primevue/usetoast';
import { onMounted, ref, watch } from 'vue';

const categoryStore = useCategoryStore()
const emit = defineEmits(['save'])
const toast = useToast()
const axiosOptions = {
    headers: {
        'Content-Type': 'multipart/form-data'
    }
}

let isUpdatingPrice = false;
let isUpdatingPriceWithTax = false;

const unities = ref([
    {label: 'Unidad', value: 'unity'},
    {label: 'Bolsa', value: 'bag'},
    {label: 'Costal', value: 'sack'},
    {label: 'Cubeta', value: 'bucket'},
    {label: 'Caja', value: 'box'},
    {label: 'Kilo', value: 'kg'},
    {label: 'Gramos', value: 'g'},
    {label: 'Litro', value: 'l'},
    {label: 'Mililitros', value: 'ml'},
    {label: 'Metros', value: 'meters'},
    {label: 'Centímetos', value: 'cm'},
    {label: 'Horas', value: 'hrs'},
    {label: 'Días', value: 'days'},
    {label: 'Semanas', value: 'weeks'},
    {label: 'Meses', value: 'months'},
])

const form = ref<Product>({
    name: '',
    code: null,
    sku: null,
    price: 0,
    price_with_tax: null,
    cost: 0,
    wholesale_price: null,
    image: null as File | null,
    image_url: null,
    quantity: 0,
    stock: 0,
    unit_type: 'Unidad',
    tax: 0,
    description: null,
    categories: [],
})

function submit() {
    axios.post(route('api.products.store'), form.value, axiosOptions)
    .then((response: AxiosResponse) => {
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 1500 })
        emit('save')
    })
    .catch(reject => {
        console.error(reject.response.data.errors);
        toast.add({ summary: 'Error', detail: reject.response.data.message, severity: 'error', life: 4000 })
    })
}

function setImage($event: Event) {
    if ($event.target instanceof HTMLInputElement) {
        const input = $event.target as HTMLInputElement;
        form.value.image = input.files?.[0] as File | null
    }
}

function calcularPrecioSinImpuesto(precioConImpuesto: number, taxNumber: number): number {
    return precioConImpuesto / (1 + (taxNumber / 100));
}

function calcularPrecioConImpuesto(precioSinImpuesto: number, taxNumber: number): number {
    return precioSinImpuesto * (1 + (taxNumber / 100));
}

// Watcher para actualizar el precio sin impuestos cuando cambia el precio con impuestos o la tasa de impuestos
watch([() => form.value.price_with_tax, () => form.value.tax], ([precioConImpuesto, tasaImpuesto]) => {
    if (isUpdatingPriceWithTax) return;

    if (precioConImpuesto != null && tasaImpuesto != null) {
        isUpdatingPrice = true;
        form.value.price = calcularPrecioSinImpuesto(precioConImpuesto, tasaImpuesto);
        isUpdatingPrice = false;
    }
});

// Watcher para actualizar el precio con impuestos cuando cambia el precio sin impuestos o la tasa de impuestos
watch([() => form.value.price, () => form.value.tax], ([precioSinImpuesto, tasaImpuesto]) => {
    if (isUpdatingPrice) return;

    if (precioSinImpuesto != null && tasaImpuesto != null) {
        isUpdatingPriceWithTax = true;
        form.value.price_with_tax = calcularPrecioConImpuesto(precioSinImpuesto, tasaImpuesto);
        isUpdatingPriceWithTax = false;
    }
});

onMounted(() => {
    categoryStore.fetchItems()
})
</script>
<template>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
        <div class="flex flex-col gap-2">
            <div>
                <label for="name" class="block">Nombre</label>
                <InputText v-model="form.name" class="w-full"></InputText>
            </div>
            <div class="flex gap-2 justify-between">
                <div class="w-full">
                    <label for="code" class="block">Código</label>
                    <InputText v-model="form.code" class="w-full"></InputText>
                </div>
                <div class="w-full">
                    <label for="sku" class="block">SKU (Opcional)</label>
                    <InputText v-model="form.sku" class="w-full"></InputText>
                </div>
            </div>
            <div class="w-full">
                <label for="unit_type" class="block">Unidad</label>
                <Select v-model="form.unit_type" class="w-full" :options="unities" optionLabel="label" optionValue="label"></Select>
            </div>
            <div class="flex gap-2 justify-between">
                <div class="w-1/2">
                    <label for="cost" class="block">Costo</label>
                    <InputNumber v-model="form.cost" showButtons :minFractionDigits="2" :maxFractionDigits="2" class="w-full" placeholder="0.00"></InputNumber>
                </div>
                <div class="w-1/2">
                    <label for="price" class="block">Precio</label>
                    <InputNumber v-model="form.price" showButtons :minFractionDigits="2" :maxFractionDigits="2" class="w-full" placeholder="0.00"></InputNumber>
                </div>
            </div>
            <div class="flex gap-2">
                <div class="w-full">
                    <label for="tax" class="block">IVA</label>
                    <InputNumber v-model="form.tax" showButtons prefix="%" :minFractionDigits="1" class="w-full" placeholder="%0.0"></InputNumber>
                </div>
                <div class="w-full">
                    <label for="tax" class="block">Precio con impuesto</label>
                    <InputNumber v-model="form.price_with_tax" showButtons :minFractionDigits="2" :maxFractionDigits="2" class="w-full" placeholder="0.00"></InputNumber>
                </div>
            </div>
            <div class="flex gap-2 items-center">
                <div class="w-full">
                    <label for="wholesale_price" class="block">Precio mayorista</label>
                    <InputNumber v-model="form.wholesale_price" showButtons :minFractionDigits="2" class="w-full" placeholder="0.00"></InputNumber>
                </div>
                <div class="w-full">
                    <label for="tax" class="block">Con impuesto</label>
                    <span v-if="form.wholesale_price != null" class="block py-2 px-3 w-full border border-1 rounded-md bg-gray-100">
                        {{ formatMoneyNumber(((form?.tax / 100) * form?.wholesale_price) + form?.wholesale_price) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-between">
            <div class="grid gap-2">
                <div class="w-full">
                    <label for="description" class="block">Categorías</label>
                    <div class="card flex justify-center">
                        <MultiSelect v-model="form.categories"
                            display="chip"
                            :options="categoryStore.categories"
                            optionLabel="name" option-value="id" placeholder="Elegir"
                            :maxSelectedLabels="5" class="w-full" />
                    </div>
                </div>
                <div>
                    <label for="description" class="block">Descripción</label>
                    <Textarea v-model="form.description" rows="5" class="w-full"></Textarea>
                </div>
                <div class="pb-4">
                    <label for="image" class="block">Imagen</label>
                    <input type="file" @input="setImage" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"/>
                </div>
            </div>
            <div>
                <form @submit.prevent="submit" >
                    <Button :style="{ width: '100%' }" type="submit" severity="success" label="Registrar" raised></Button>
                </form>
            </div>
        </div>
    </div>
</template>