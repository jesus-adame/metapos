<script lang="ts" setup>
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import axios from 'axios';
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import { Location } from '@/types';

const props = defineProps<{
    location: Location | null
}>();
const toast = useToast();
const form = ref({
    name: props.location?.name,
    address: props.location?.address,
    phone_number: props.location?.phone_number,
    rfc: props.location?.rfc,
    email: props.location?.email,
    timezone: props.location?.timezone,
    type: 'branch',
    _method: 'put',
});

const emit = defineEmits(['save', 'cancel'])

const submit = () => {
    axios.post(route('api.locations.update', {location: props.location?.id}), form.value)
    .then(response => {
        form.value = {
            name: '',
            address: '',
            phone_number: '',
            rfc: '',
            email: '',
            timezone: '',
            type: 'branch',
            _method: 'put',
        };

        emit('save')
        toast.add({ severity: 'success', summary: 'Correcto', detail: response.data.message, life: 1200 });
    })
    .catch(({ response }) => {
        console.log(response);
        toast.add({ severity: 'error', summary: 'Error', detail: response.data.message, life: 2100 });
    })
};
</script>
<template>
    <form @submit.prevent="submit">
        <div>
            <InputLabel for="name" value="Nombre" />
            <InputText
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
            />
        </div>
        <div class="flex gap-2 mt-4">
            <div class="w-1/2">
                <InputLabel for="email" value="Teléfono" />
                <InputText
                    class="mt-1 block w-full"
                    v-model="form.phone_number"
                />
            </div>
            <div class="w-1/2">
                <InputLabel for="rfc" value="RFC" />
                <InputText
                    class="mt-1 block w-full"
                    v-model="form.rfc"
                />
            </div>
        </div>
        <div class="mt-4">
            <InputLabel for="email" value="Email" />
            <InputText
                class="mt-1 block w-full"
                v-model="form.email"
            />
        </div>
        <div class="mt-4">
            <InputLabel for="email" value="Dirección" />
            <InputText
                class="mt-1 block w-full"
                v-model="form.address"
            />
        </div>
        <div class="mt-4">
            <InputLabel for="email" value="Zona horaria" />
            <InputText
                class="mt-1 block w-full"
                v-model="form.timezone"
            />
        </div>
        <div class="flex items-center justify-end mt-4">
            <Button raised label="Aplicar" type="submit" class="ms-4" severity="warn"</Button>
        </div>
    </form>
</template>