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
    <table class="w-full text-right bg-white">
        <tr>
            <td class="p-2 border">
            </td>
            <td class="p-2 border">
                <span class="font-bold">
                    Efectivo
                </span>
            </td>
            <td class="p-2 border">
                <span class="font-bold">
                    Tarjeta
                </span>
            </td>
            <td class="p-2 border">
                <span class="font-bold">
                    Tranferencias
                </span>
            </td>
            <td class="p-2 border">
                <span class="font-bold">
                    Totales
                </span>
            </td>
        </tr>
        <tr>
            <td class="p-2 border">
                <span class="font-bold">
                    Entradas
                </span>
            </td>
            <td class="p-2 border">{{ formatMoneyNumber(cash?.entries) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(card?.entries) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(transfer?.entries) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(global?.entries) }}</td>
        </tr>
        <tr>
            <td class="p-2 border bg-white">
                <span class="font-bold">
                    Salidas
                </span>
            </td>
            <td class="p-2 border">{{ formatMoneyNumber(cash?.exits) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(card?.exits) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(transfer?.exits) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(global?.exits) }}</td>
        </tr>
        <tr>
            <td class="p-2 border bg-white">
                <span class="font-bold">
                    Saldo
                </span>
            </td>
            <td class="p-2 border">{{ formatMoneyNumber(cash?.balance) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(card?.balance) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(transfer?.balance) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(global?.balance) }}</td>
        </tr>
    </table>
</template>