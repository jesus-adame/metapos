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

const form = ref({
    code: null,
    name: null,
    description: null,
    price: null,
    image: null as File | null,
})

function submit() {
    axios.post(route('products.store'), form.value, axiosOptions)
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
        <div>
            <label for="price" class="block">Precio</label>
            <InputNumber v-model="form.price" showButtons class="w-full"></InputNumber>
        </div>
        <div>
            <label for="image" class="block">Imagen</label>
            <input type="file" @input="setImage" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"/>
        </div><br>
        <Button type="submit">Registrar</Button>
    </form>
</template>