<script lang="ts" setup>
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import axios, { AxiosResponse } from 'axios';
import { onMounted, ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import { Role, User } from '@/types';
import Checkbox from 'primevue/checkbox';

const props = defineProps<{
    user: User | null
}>();
const toast = useToast();
const form = ref<{
    name: string | undefined,
    lastname: string | undefined,
    email: string | undefined,
    password: string | undefined,
    password_confirmation: string | undefined,
    roles: string[] | undefined,
    _method: string
}>({
    name: props.user?.name,
    lastname: props.user?.lastname,
    email: props.user?.email,
    password: '',
    password_confirmation: '',
    roles: props.user?.roles?.map(role => role.name),
    _method: 'PUT',
});

const roles = ref<Role[]>([])

const emit = defineEmits(['save', 'cancel'])

const fetchRoles = () => {
    axios.get(route('api.roles.index'))
    .then((response: AxiosResponse) => {
        const paginate = response.data
        roles.value = paginate.data
    })
}

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
            roles: [],
        };

        toast.add({ severity: 'success', summary: 'Correcto', detail: response.data.message, life: 1200 });
        emit('save')
    })
    .catch(({ response }) => {
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
        <div class="flex items-center justify-end mt-4">
            <Button label="Guardar" type="submit" class="ms-4"></Button>
        </div>
    </form>
</template>