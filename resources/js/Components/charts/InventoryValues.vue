<script lang="ts" setup>
import { formatMoneyNumber } from '@/helpers';
import axios, { AxiosResponse } from 'axios';
import { onMounted, ref } from 'vue';

const data = ref({
    totalValue: 0,
    totalCost: 0,
    totalMargin: 0
})

onMounted(() => {
    axios.get(route('api.charts.inventory-values'))
    .then((response: AxiosResponse) => {
        data.value = response.data
    })
})
</script>
<template>
    <div class="bg-white rounded shadow-sm text-center p-8">
        <div class="h-full flex flex-col">
            <h4 class="text-2xl pt-2">Valor del inventario</h4>

            <div class="h-full flex flex-col justify-around p-6">
                <div class="block">
                    <p class="text-gray-700">Valor total</p>
                    <span class="text-3xl font-bold">{{ formatMoneyNumber(data.totalValue) }}</span>
                </div>
                <div>
                    <p class="text-gray-700">Costo total</p>
                    <span class="text-3xl font-bold">{{ formatMoneyNumber(data.totalCost) }}</span>
                </div>
                <div>
                    <p class="text-gray-700">Margen total</p>
                    <span class="text-3xl font-bold">{{ formatMoneyNumber(data.totalMargin) }}</span>
                </div>
            </div>
        </div>
    </div>
</template>