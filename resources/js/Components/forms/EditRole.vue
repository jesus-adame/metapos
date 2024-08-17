<script lang="ts" setup>
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import axios, { AxiosResponse } from 'axios';
import { onMounted, ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import { Permission, Role } from '@/types';
import Checkbox from 'primevue/checkbox';

const props = defineProps<{
    role: Role | null
}>();
const toast = useToast();
const form = ref<{
    name: string | undefined,
    guard: string,
    permissions: string[] | undefined,
    _method: string
}>({
    name: props.role?.name,
    guard: 'web',
    permissions: props.role?.permissions?.map(role => role.name),
    _method: 'put'
});

const permissions = ref<Permission[]>([])
const emit = defineEmits(['save', 'cancel'])

const submit = () => {
    axios.post(route('api.roles.update', {role: props.role?.id}), form.value)
    .then(response => {
        form.value = {
            name: '',
            guard: 'web',
            permissions: [],
            _method: 'put'
        };

        toast.add({ severity: 'success', summary: 'Correcto', detail: response.data.message, life: 1200 });
        emit('save')
    })
    .catch(({ response }) => {
        console.log(response);
        toast.add({ severity: 'error', summary: 'Error', detail: response.data.message, life: 2100 });
    })
};

const fetchPermissions = () => {
    axios.get(route('api.permissions.index'))
    .then((response: AxiosResponse) => {
        const data = response.data
        permissions.value = data.permissions
    })
}

onMounted(() => {
    fetchPermissions()
})
</script>
<template>
    <form @submit.prevent="submit">
        <div class="w-full mr-2">
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
        <!-- <div class="w-full mt-2">
            <InputLabel for="lastname" value="Guarda" />
            <InputText
                id="lastname"
                class="mt-1 block w-full"
                v-model="form.guard"
                autocomplete="lastname"
            />
        </div> -->
        <div class="mb-4 w-full flex flex-wrap">
            <div class="w-1/2 my-2" v-for="(permission, index) in permissions" :key="index">
                <Checkbox v-model="form.permissions" :input-id="'permission' + index" :value="permission.name" />
                <label :for="'permission' + index" class="ml-2">{{ permission.name }}</label>
            </div>
        </div>
        <div class="flex items-center justify-end mt-4">
            <Button label="Guardar" type="submit" class="ms-4"></Button>
        </div>
    </form>
</template>