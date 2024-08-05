<script lang="ts" setup>
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputLabel from '@/Components/InputLabel.vue';
import axios, { AxiosResponse } from 'axios';
import { onMounted, ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import Select from 'primevue/select';
import BranchService from "@/Services/BranchService";

const branchService: BranchService = new BranchService()
const toast = useToast();
const form = ref({
    name: '',
    branch_id: ''
});
const branches = ref([])

const emit = defineEmits(['save', 'cancel'])

const submit = () => {
    axios.post(route('cash-registers.store'), form.value)
    .then(response => {
        form.value = {
            name: '',
            branch_id: ''
        };

        toast.add({ severity: 'success', summary: 'Correcto', detail: 'Registrado correctamente', life: 1200 });
        emit('save')
    })
    .catch(({ response }) => {
        console.error(response);
        toast.add({ severity: 'error', summary: 'Error', detail: response.data.message, life: 2100 });
    })
};

const fetchBranches = () => {
    const result = branchService.fetchAll()

    result.then((response: AxiosResponse) => {
        const dataResponse = response.data;
        branches.value = JSON.parse(JSON.stringify(dataResponse.data))
    })
}

onMounted(() => {
    fetchBranches()
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
            <Select v-model="form.branch_id" :options="branches" optionLabel="name" optionValue="id" class="w-full"></Select>
        </div>
        <div class="flex items-center justify-end mt-4">
            <Button label="Guardar" type="submit" class="ms-4"></Button>
        </div>
    </form>
</template>