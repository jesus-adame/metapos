<script lang="ts" setup>
import { formatMoneyNumber } from '@/helpers';
import CashFlowService from '@/Services/CashFlowService';
import { useAuthStore } from '@/stores/AuthStore';
import { AxiosResponse } from 'axios';
import { onMounted, ref, watch } from 'vue';

const cashFlowService = new CashFlowService;
const global = ref<any>(null)
const cash = ref<any>(null)
const card = ref<any>(null)
const transfer = ref<any>(null)
const authStore = useAuthStore()

const fetchItems = () => {
    cashFlowService.resume()
    .then((response: AxiosResponse) => {
        global.value = response.data.global
        cash.value = response.data.cash
        card.value = response.data.card
        transfer.value = response.data.transfer
    })
}

// Suscribirse a las acciones del store
watch(() => authStore.cashRegister, () => {
    fetchItems()
})

onMounted(() => {
    fetchItems()
})
</script>
<template>
    <div class="flex items-end rounded flex-end text-right text-lg text-gray-600 w-full p-2">
        <div class="">
            <p class="font-bold p-1 rounded m-1">Entradas</p>
            <p class="font-bold p-1 rounded m-1">Salidas</p>
            <span class="text-gray-700">-------</span>
            <p class="font-bold p-1 rounded m-1">Saldo</p>
        </div>
        <div class="">
            <p class="font-bold">Efectivo</p>
            <p class="p-1 rounded m-1 bg-green-200">{{ formatMoneyNumber(cash?.entries) }}</p>
            <p class="p-1 rounded m-1 bg-red-200">{{ formatMoneyNumber(cash?.exits) }}</p>
            <span class="text-gray-700">-----------------</span>
            <p class="p-1 rounded m-1 bg-blue-200">{{ formatMoneyNumber(cash?.balance) }}</p>
        </div>
        <div class="">
            <p class="font-bold">Tarjeta</p>
            <p class="p-1 rounded m-1 bg-green-200">{{ formatMoneyNumber(card?.entries) }}</p>
            <p class="p-1 rounded m-1 bg-red-200">{{ formatMoneyNumber(card?.exits) }}</p>
            <span class="text-gray-700">-----------------</span>
            <p class="p-1 rounded m-1 bg-blue-200">{{ formatMoneyNumber(card?.balance) }}</p>
        </div>
        <div class="">
            <p class="font-bold">Transferencia</p>
            <p class="p-1 rounded m-1 bg-green-200">{{ formatMoneyNumber(transfer?.entries) }}</p>
            <p class="p-1 rounded m-1 bg-red-200">{{ formatMoneyNumber(transfer?.exits) }}</p>
            <span class="text-gray-700">-----------------</span>
            <p class="p-1 rounded m-1 bg-blue-200">{{ formatMoneyNumber(transfer?.balance) }}</p>
        </div>
        <div class="">
            <p class="font-bold">Totales</p>
            <p class="p-1 rounded m-1 bg-green-200">{{ formatMoneyNumber(global?.entries) }}</p>
            <p class="p-1 rounded m-1 bg-red-200">{{ formatMoneyNumber(global?.exits) }}</p>
            <span class="text-gray-700">-----------------</span>
            <p class="p-1 rounded m-1 bg-blue-200">{{ formatMoneyNumber(global?.balance) }}</p>
        </div>
    </div>
</template>