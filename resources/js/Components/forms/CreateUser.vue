<script lang="ts" setup>
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import axios from 'axios';
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';

const toast = useToast();
const form = ref({
    name: '',
    lastname: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const emit = defineEmits(['save', 'cancel'])

const submit = () => {
    axios.post(route('api.users.store'), form.value)
    .then(response => {
        form.value = {
            name: '',
            lastname: '',
            email: '',
            password: '',
            password_confirmation: '',
        };

        toast.add({ severity: 'success', summary: 'Correcto', detail: response.data.message, life: 1200 });
        emit('save')
    })
    .catch(({ response }) => {
        console.log(response);
        toast.add({ severity: 'error', summary: 'Error', detail: response.data.message, life: 2100 });
    })
};
</script>
<template>
    <form @submit.prevent="submit">
        <div class="flex">
            <div class="w-1/2 mr-2">
                <InputLabel for="name" value="Nombre" />
                <InputText
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autocomplete="name"
                />
            </div>
            <div class="w-1/2">
                <InputLabel for="lastname" value="Apellidos" />
                <InputText
                    id="lastname"
                    class="mt-1 block w-full"
                    v-model="form.lastname"
                    autocomplete="lastname"
                />
            </div>
        </div>
        <div class="mt-4">
            <InputLabel for="email" value="Email" />
            <InputText
                id="email"
                type="email"
                class="mt-1 block w-full"
                v-model="form.email"
                required
                autocomplete="username"
            />
        </div>
        <div class="mt-4">
            <InputLabel for="password" value="Password" />
            <InputText
                id="password"
                type="password"
                class="mt-1 block w-full"
                v-model="form.password"
                required
                autocomplete="new-password"
            />
        </div>
        <div class="mt-4">
            <InputLabel for="password_confirmation" value="Confirm Password" />
            <InputText
                id="password_confirmation"
                type="password"
                class="mt-1 block w-full"
                v-model="form.password_confirmation"
                required
                autocomplete="new-password"
            />
        </div>
        <div class="flex items-center justify-end mt-4">
            <Button label="Guardar" type="submit" class="ms-4" severity="success" raised></Button>
        </div>
    </form>
</template>