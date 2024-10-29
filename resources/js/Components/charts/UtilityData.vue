<script lang="ts" setup>
import { formatMoneyNumber } from '@/helpers';
import axios, { AxiosResponse } from 'axios';
import moment from 'moment-timezone';
import DatePicker from 'primevue/datepicker';
import { onMounted, ref, watch } from 'vue';

const dateStart = ref()
const dateEnd = ref()
const dates = ref()
const data = ref({
    sales: 0,
    purchases: 0,
    expenses: 0,
    utility: 0,
})

const getData = () => {
    axios.post(route('api.charts.utility'), { dates: dates.value })
    .then((response: AxiosResponse) => {
        data.value = response.data
    })
}

onMounted(() => {
    dateStart.value = moment().startOf('month')
    dateEnd.value = moment().endOf('month')

    dates.value = [
        moment().startOf('month').toDate(),
        moment().endOf('month').toDate(),
    ]

    getData()
})

watch(dates, () => {
    getData()
})
</script>
<template>
    <div class="bg-white rounded shadow-sm text-center p-8">
        <div class="h-full flex flex-col">
            <DatePicker v-model="dates" selectionMode="range"/>

            <h4 class="text-2xl pt-2">Reporte general</h4>

            <div class="h-full flex flex-col justify-around p-6">
                <div class="flex justify-between">
                    <p class="text-gray-700 text-xl">
                        <i class="pi pi-arrow-up text-green-600"></i>
                        Ventas totales
                    </p>
                    <span class="text-3xl text-green-600 font-bold">{{ formatMoneyNumber(data.sales) }}</span>
                </div>
                <div class="flex justify-between">
                    <p class="text-gray-700 text-xl">
                        <i class="pi pi-arrow-down text-red-600"></i>
                        Compras totales
                    </p>
                    <span class="text-3xl text-red-600 font-bold">{{ formatMoneyNumber(data.purchases) }}</span>
                </div>
                <div class="flex justify-between">
                    <p class="text-gray-700 text-xl">
                        <i class="pi pi-arrow-down text-red-600"></i>
                        Gastos totales
                    </p>
                    <span class="text-3xl text-red-600 font-bold">{{ formatMoneyNumber(data.expenses) }}</span>
                </div>
                <div class="flex justify-between">
                    <p class="text-gray-700 text-xl">
                        <i class="pi pi-calculator text-blue-600"></i>
                        Utilidad
                    </p>
                    <span class="text-3xl font-bold text-blue-700">{{ formatMoneyNumber(data.utility) }}</span>
                </div>
            </div>
        </div>
    </div>
</template>