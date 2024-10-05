<script lang="ts" setup>
import { formatMoneyNumber } from '@/helpers';
import CashFlowService from '@/Services/CashFlowService';
import { useAuthStore } from '@/stores/AuthStore';
import { AxiosResponse } from 'axios';
import { onMounted, ref, watch } from 'vue';

const cashFlowService = new CashFlowService;
const authStore = useAuthStore()
const expenses = ref<any>(null)
const purchases = ref<any>(null)
const sales = ref<any>(null)
const balance = ref<any>(null)

const fetchItems = () => {
    cashFlowService.resume()
    .then((response: AxiosResponse) => {
        expenses.value = response.data.expenses
        purchases.value = response.data.purchases
        sales.value = response.data.sales
        balance.value = response.data.balance
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
                    {{authStore.location?.name}}
                </span>
            </td>
        </tr>
        <tr>
            <td class="p-2 border">
                <span class="font-bold">
                    Ventas
                </span>
            </td>
            <td class="p-2 border">{{ formatMoneyNumber(sales) }}</td>
            <!-- <td class="p-2 border">{{ formatMoneyNumber(card?.entries) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(transfer?.entries) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(global?.entries) }}</td> -->
        </tr>
        <tr>
            <td class="p-2 border bg-white">
                <span class="font-bold">
                    Compras
                </span>
            </td>
            <td class="p-2 border">{{ formatMoneyNumber(purchases) }}</td>
            <!-- <td class="p-2 border">{{ formatMoneyNumber(card?.exits) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(transfer?.exits) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(global?.exits) }}</td> -->
        </tr>
        <tr>
            <td class="p-2 border bg-white">
                <span class="font-bold">
                    Gastos
                </span>
            </td>
            <td class="p-2 border">{{ formatMoneyNumber(expenses) }}</td>
            <!-- <td class="p-2 border">{{ formatMoneyNumber(card?.balance) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(transfer?.balance) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(global?.balance) }}</td> -->
        </tr>
        <tr>
            <td class="p-2 border bg-white">
                <span class="font-bold">
                    Ingresos - egresos
                </span>
            </td>
            <td class="p-2 border">{{ formatMoneyNumber(balance) }}</td>
            <!-- <td class="p-2 border">{{ formatMoneyNumber(card?.balance) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(transfer?.balance) }}</td>
            <td class="p-2 border">{{ formatMoneyNumber(global?.balance) }}</td> -->
        </tr>
    </table>
</template>