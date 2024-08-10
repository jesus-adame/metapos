<script setup>
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';
import moment from 'moment-timezone';

const toast = useToast();
const emit = defineEmits(['cancel', 'save']);
const form = ref({
    cut_date: '',
    cut_end_date: '',
    timezone: moment.tz.guess()
});

const cancel = () => {
    emit('cancel')
}

const save = () => {
    emit('save')
}

const submit = () => {
    axios.post(route('api.cash-cuts.store'), form.value)
    .then(response => {
        form.value.cut_date = '';
        save();
    })
    .catch(({ response }) => {
        console.log(response.data.message);
        toast.add({ severity: 'error', summary: 'Error', detail: response.data.message, life: 2000 });
    })
};
</script>
<template>
    <form @submit.prevent="submit">
        <div class="my-4">
            <div class="flex">
                <div class="w-1/2 mr-2">
                    <label>Desde</label>
                    <DatePicker class="w-full" v-model="form.cut_date" dateFormat="dd/mm/yy" iconDisplay="input" showIcon></DatePicker>
                </div>
                <div class="w-1/2">
                    <label>Hasta</label>
                    <DatePicker class="w-full" v-model="form.cut_end_date" dateFormat="dd/mm/yy" iconDisplay="input" showIcon></DatePicker>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <Button type="submit" :disabled="form.processing" label="Guardar" severity="success"></Button>
        </div>
    </form>
</template>
