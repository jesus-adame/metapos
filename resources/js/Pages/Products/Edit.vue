<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3'
import Button from 'primevue/button';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';

const props = defineProps({
    product: Object
})

const form = useForm({
    'name': props.product.name,
    'code': props.product.code,
    'description': props.product.description,
    'price': props.product.price,
    'image': null,
    _method: 'put'
})



function submit() {
    form.post(route('products.update', props.product.id), {
        forceFormData: true, // Ensure formData is used
        preserveScroll: true, // Preserve scroll position
    });
}
</script>

<template>
    <Head title="Product" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
        </template>

        <div class="py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form @submit.prevent="submit">
                        <div>
                            <label for="code" class="block">Código</label>
                            <InputText v-model="form.code" required></InputText>
                        </div>
                        <div>
                            <label for="name" class="block">Nombre</label>
                            <InputText v-model="form.name" required></InputText>
                        </div>
                        <div>
                            <label for="description" class="block">Descripción</label>
                            <Textarea v-model="form.description" autoResize rows="5" class="w-56"></Textarea>
                        </div>
                        <div>
                            <label for="price" class="block">Precio</label>
                            <InputNumber v-model="form.price" :min="0.01" :step="0.01" required showButtons mode="currency" currency="MXN" :minFractionDigits="2"></InputNumber>
                        </div>
                        <div>
                            <label for="image" class="block">Imagen</label>
                            <div v-if="props.product.image" class="my-2">
                                <img :src="props.product.image_url" alt="Product Image" width="100">
                            </div>
                            <input type="file" @input="form.image = $event.target.files[0]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded border py-2 px-3 shadow-sm"/>
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                {{ form.progress.percentage }}%
                            </progress>
                        </div><br>
                        <Button type="submit" :disabled="form.processing">Actualizar</Button>
                    </form>
                </div>
            </div>
        </div>
    </UserLayout>
</template>