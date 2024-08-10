<script lang="ts" setup>
import { Product } from '@/types';
import { usePage } from '@inertiajs/vue3';
import axios, { AxiosResponse } from 'axios';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';

const page = usePage()
const emit = defineEmits(['save'])
const toast = useToast()
const axiosOptions = {
    headers: {
        'Content-Type': 'multipart/form-data'
    }
}

const unities = ref([
    {label: 'Unidad', value: 'unity'},
    {label: 'Bolsa', value: 'bag'},
    {label: 'Caja', value: 'box'},
    {label: 'Kilo', value: 'kg'},
    {label: 'Litro', value: 'l'},
    {label: 'Gramos', value: 'g'},
    {label: 'Metros', value: 'meters'},
])

const { product } = defineProps<{
    product: Product | undefined
}>()

const form = ref({
    name: product?.name,
    code: product?.code,
    description: product?.description,
    price: product?.price,
    image: null as File | null,
    unit_type: product?.unit_type,
    _method: 'put'
})

function submit() {
    axios.post(route('products.update', { product: product?.id }), form.value, axiosOptions)
    .then((response: AxiosResponse) => {
        console.log(response);
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 1500 })
        emit('save')
    })
    .catch(reject => {
        console.error(reject.response.data.errors);
        toast.add({ summary: 'Error', detail: reject.response.data.message, severity: 'error', life: 3000 })
    })
}

function setImage($event: Event) {
    if ($event.target instanceof HTMLInputElement) {
        const input = $event.target as HTMLInputElement;
        form.value.image = input.files?.[0] as File | null
    }
}
</script>
<template>
    <form @submit.prevent="submit">
        <div>
            <label for="code" class="block">Código</label>
            <InputText v-model="form.code" required class="w-full"></InputText>
        </div>
        <div>
            <label for="name" class="block">Nombre</label>
            <InputText v-model="form.name" class="w-full"></InputText>
        </div>
        <div>
            <label for="description" class="block">Descripción</label>
            <Textarea v-model="form.description" rows="5" class="w-full"></Textarea>
        </div>
        <div class="flex justify-between">
            <div class="mr-2">
                <label for="price" class="block">Precio</label>
                <InputNumber v-model="form.price" showButtons class="w-full"></InputNumber>
            </div>
            <div>
                <label for="unit_type" class="block">Unidad</label>
                <Select v-model="form.unit_type" class="w-full" :options="unities" optionLabel="label" optionValue="label"></Select>
            </div>
        </div>
        <div>
            <label for="image" class="block">Imagen</label>
            <div v-if="product?.image" class="my-2">
                <img :src="product?.image_url" alt="Product Image" width="100">
            </div>
            <input type="file" @input="setImage" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"/>
        </div><br>
        <Button type="submit">Actualizar</Button>
    </form>
</template>