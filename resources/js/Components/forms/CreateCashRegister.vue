<script lang="ts" setup>
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import axios, { AxiosResponse } from 'axios';
import { onMounted, ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import Select from 'primevue/select';
import LocationService from "@/Services/LocationService";

const locationService: LocationService = new LocationService()
const toast = useToast();
const form = ref({
    name: '',
    location_id: '',
});
const locations = ref([])

const emit = defineEmits(['save', 'cancel'])

const submit = () => {
    axios.post(route('api.cash-registers.store'), form.value)
    .then(response => {
        form.value = {
            name: '',
            location_id: '',
        };

        toast.add({ severity: 'success', summary: 'Correcto', detail: response.data.message, life: 1200 });
        emit('save')
    })
    .catch(({ response }) => {
        console.error(response);
        toast.add({ severity: 'error', summary: 'Error', detail: response.data.message, life: 2100 });
    })
};

const fetchLocationes = () => {
    const result = locationService.fetchAll()

    result.then((response: AxiosResponse) => {
        const dataResponse = response.data;
        locations.value = JSON.parse(JSON.stringify(dataResponse.data))
    })
}

onMounted(() => {
    fetchLocationes()
})
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
                autocomplete="name"
            />
        </div>
        <div class="mt-4">
            <InputLabel for="banches" value="Ubicación" />
            <Select v-model="form.location_id" :options="locations" optionLabel="name" optionValue="id" class="w-full"></Select>
        </div>
        <div class="flex items-center justify-end mt-4">
            <Button label="Guardar" type="submit" class="ms-4"></Button>
        </div>
    </form>
</template>