<script lang="ts" setup>
import axios, { AxiosResponse } from 'axios';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';

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

const form = ref({
    code: null,
    name: null,
    description: null,
    price: null,
    cost: null,
    sku: null,
    image: null as File | null,
    unit_type: 'Unidad',
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
</script>
<template>
    <form @submit.prevent="submit">
        <div>
            <label for="code" class="block">Código</label>
            <InputText v-model="form.code" class="w-full"></InputText>
        </div>
        <div>
            <label for="name" class="block">Nombre</label>
            <InputText v-model="form.name" class="w-full"></InputText>
        </div>
        <div class="flex justify-between">
            <div class="mr-2">
                <label for="price" class="block">Precio</label>
                <InputNumber v-model="form.price" showButtons class="w-full" placeholder="0.00"></InputNumber>
            </div>
            <div>
                <label for="cost" class="block">Costo</label>
                <InputNumber v-model="form.cost" showButtons class="w-full" placeholder="0.00"></InputNumber>
            </div>
        </div>
        <div class="flex justify-between">
            <div class="w-full mr-2">
                <label for="unit_type" class="block">Unidad</label>
                <Select v-model="form.unit_type" class="w-full" :options="unities" optionLabel="label" optionValue="label"></Select>
            </div>
            <div class="w-full">
                <label for="sku" class="block">SKU (Opcional)</label>
                <InputText v-model="form.sku" class="w-full"></InputText>
            </div>
        </div>
        <div>
            <label for="description" class="block">Descripción</label>
            <Textarea v-model="form.description" rows="5" class="w-full"></Textarea>
        </div>
        <div>
            <label for="image" class="block">Imagen</label>
            <input type="file" @input="setImage" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"/>
        </div><br>
        <Button type="submit" severity="success" raised>Registrar</Button>
    </form>
</template>