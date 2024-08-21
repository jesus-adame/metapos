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
    guard: 'web',
});

const emit = defineEmits(['save', 'cancel'])

const submit = () => {
    axios.post(route('api.roles.store'), form.value)
    .then(response => {
        form.value = {
            name: '',
            guard: 'web',
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
        <div class="flex items-center justify-end mt-4">
            <Button raised label="Guardar" type="submit" class="ms-4" severity="success"></Button>
        </div>
    </form>
</template>