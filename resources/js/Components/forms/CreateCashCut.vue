<script setup lang="ts">
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';
import moment from 'moment-timezone';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';

const toast = useToast();
const emit = defineEmits(['cancel', 'save']);
const form = ref({
    cut_date: '',
    cut_end_date: '',
    cash: null,
    card: null,
    transfer: null,
    timezone: moment.tz.guess(),
    processing: false
});

const cancel = () => {
    emit('cancel')
}

const save = () => {
    emit('save')
}

const submit = () => {
    form.value.processing = true
    axios.post(route('api.cash-cuts.store'), form.value)
    .then(response => {
        form.value.cut_date = '';
        save();
        form.value.processing = false
    })
    .catch(({ response }) => {
        console.log(response.data.message);
        toast.add({ severity: 'error', summary: 'Error', detail: response.data.message, life: 2000 });
        form.value.processing = false
    })
};
</script>
<template>
    <div class="grid gap-2">
        <div class="flex">
            <div class="w-1/2 mr-2">
                <label>Desde</label>
                <DatePicker class="w-full" v-model="form.cut_date" dateFormat="dd/mm/yy" iconDisplay="input" showIcon placeholder="dd/mm/yyyy"></DatePicker>
            </div>
            <div class="w-1/2">
                <label>Hasta</label>
                <DatePicker class="w-full" v-model="form.cut_end_date" dateFormat="dd/mm/yy" iconDisplay="input" showIcon placeholder="dd/mm/yyyy"></DatePicker>
            </div>
        </div>
        <div class="w-full">
            <label>Efectivo</label>
            <InputNumber v-model="form.cash" fluid showButtons placeholder="0.00"></InputNumber>
        </div>
        <div class="w-full">
            <label>Tarjeta</label>
            <InputNumber v-model="form.card" fluid showButtons placeholder="0.00"></InputNumber>
        </div>
        <div class="w-full">
            <label>Transferencia</label>
            <InputNumber v-model="form.transfer" fluid showButtons placeholder="0.00"></InputNumber>
        </div>
    </div>
    <div class="flex items-center justify-end mt-4">
        <form @submit.prevent="submit">
            <Button label="Guardar" type="submit" class="ms-4" severity="success" raised></Button>
        </form>
    </div>
</template>
