<script lang="ts" setup>
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import axios from 'axios';
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import { User } from '@/types';

const props = defineProps<{
    user: User | null
}>();
const toast = useToast();
const form = ref({
    name: props.user?.name,
    lastname: props.user?.lastname,
    email: props.user?.email,
    password: '',
    password_confirmation: '',
    _method: 'PUT',
});

const emit = defineEmits(['save', 'cancel'])

const submit = () => {
    axios.post(route('api.users.update', {user: props.user?.id}), form.value)
    .then(response => {
        form.value = {
            name: '',
            lastname: '',
            email: '',
            password: '',
            password_confirmation: '',
            _method: 'PUT',
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
                autocomplete="new-password"
            />
        </div>
        <div class="flex items-center justify-end mt-4">
            <Button label="Guardar" type="submit" class="ms-4"></Button>
        </div>
    </form>
</template>