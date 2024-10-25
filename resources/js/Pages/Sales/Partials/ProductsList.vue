<script setup lang="ts">
import Card from '@/Components/Card.vue';
import { formatMoneyNumber, percentageNumber } from '@/helpers';
import { CartItem } from '@/types';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Image from 'primevue/image';
import InputNumber from 'primevue/inputnumber';
import Message from 'primevue/message';

const props = defineProps<{
    products: CartItem[]
}>()

const emit = defineEmits(['updateProducts'])

const removeProduct = (index: number) => {
    props.products.splice(index, 1)
};
</script>
<template>
    <Message severity="info" icon="pi pi-info-circle" :closable="false" v-if="products.length <= 0" :pt="{ text: 'text-lg' }">El carrito está vacío</Message>

    <Card width="full" padding="0">
        <DataTable :value="products" v-if="products.length">
            <Column header="Concepto">
                <template #body="{data}">
                    <div class="flex gap-2 items-center">
                        <div v-if="data.image" class="overflow-hidden hidden xl:block shadow-lg rounded-md min-w-14 max-w-14 max-h-14">
                            <Image :src="data.image_url" :alt="data.name" />
                        </div>
                        <div class="text-left max-w-48">
                            <p class="font-bold truncate">{{ data.name }}</p>
                            <p class="text-sm font-medium text-gray-500">{{ data.code }}</p>
                            <p class="text-sm font-medium text-gray-500">SKU: {{ data.sku || 'N/A' }}</p>
                        </div>
                    </div>
                </template>
            </Column>
            <Column header="Cantidad">
                <template #body="{data}">
                    <InputNumber v-model="data.quantity" showButtons buttonLayout="vertical" style="width: 3rem" :min="0.1" :maxFractionDigits="2" :max="data.stock"></InputNumber>
                    <!-- <span class="text-center block">{{data.quantity}}</span> -->
                </template>
            </Column>
            <Column header="Precio">
                <template #body="{data}">
                    <span class="block text-end font-medium">
                        {{ formatMoneyNumber(data.price) }}
                        <p>IVA {{ percentageNumber(data.tax ?? 0) }}</p>
                    </span>
                </template>
            </Column>
            <Column header="Subtotal">
                <template #body="{data}">
                    <span class="block text-end font-medium">
                        {{ formatMoneyNumber(data.price * data.quantity) }}
                    </span>
                </template>
            </Column>
            <Column header="">
                <template #body="slot">
                    <Button severity="danger" icon="pi pi-times" @click="removeProduct(slot.index)"></Button>
                </template>
            </Column>
        </DataTable>
    </Card>
</template>