<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3'
import Button from 'primevue/button';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';

const form = useForm({
    'code': null,
    'name': null,
    'description': null,
    'price': null,
    'image': null,
})

function submit() {
  form.post(route('products.store'))
}
</script>

<template>
    <Head title="Dashboard" />

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
                            <InputText v-model="form.name"></InputText>
                        </div>
                        <div>
                            <label for="description" class="block">Descripción</label>
                            <Textarea v-model="form.description" rows="5"></Textarea>
                        </div>
                        <div>
                            <label for="price" class="block">Precio</label>
                            <InputNumber v-model="form.price" showButtons></InputNumber>
                        </div>
                        <div>
                            <label for="image" class="block">Imagen</label>
                            <input type="file" @input="form.image = $event.target.files[0]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"/>
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                {{ form.progress.percentage }}%
                            </progress>
                        </div><br>
                        <Button type="submit" :disabled="form.processing">Registrar</Button>
                    </form>

                </div>
            </div>
        </div>
    </UserLayout>
</template>