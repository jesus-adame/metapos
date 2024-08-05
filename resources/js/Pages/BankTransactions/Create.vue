<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';
import DatePicker from 'primevue/datepicker';

const form = useForm({
    type: 'entry',
    amount: null,
    description: '',
    transaction_date: '',
});

const types = ref([
    { name: 'Entrada', code: 'entry' },
    { name: 'Salida', code: 'exit' },
])

const submit = () => {
    form.post(route('bank-transactions.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Record Bank Transaction" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Record Bank Transaction</h2>
        </template>

        <div class="py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label for="type" class="block">Tipo</label>
                            <Select v-model="form.type" :options="types" optionLabel="name" class="w-full md:w-56" placeholder="Elegir"></Select>
                        </div>
                        <div class="mb-4">
                            <label for="amount" class="block">Monto</label>
                            <InputNumber v-model="form.amount" showButtons min="0" placeholder="0" class="w-56"></InputNumber>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block">Descripción</label>
                            <Textarea v-model="form.description" id="description" rows="5" class="w-56 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></Textarea>
                        </div>
                        <div class="mb-4">
                            <label for="transaction_date" class="block">Fecha de transacción</label>
                            <DatePicker v-model="form.transaction_date" showIcon fluid iconDisplay="input" class="w-56" />
                        </div>
                        <div class="mt-6">
                            <Button type="submit" :disabled="form.processing">Record Bank Transaction</Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </UserLayout>
</template>
