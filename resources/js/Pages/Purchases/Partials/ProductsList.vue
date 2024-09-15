<script setup lang="ts">
import Card from '@/Components/Card.vue';
import { formatMoneyNumber } from '@/helpers';
import { Product } from '@/types';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Image from 'primevue/image';
import InputNumber from 'primevue/inputnumber';
import Message from 'primevue/message';

const props = defineProps<{
    products: Product[]
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
            <Column header="Producto">
                <template #body="{data}">
                    <div class="flex">
                        <div v-if="data.image" class="overflow-hidden hidden lg:block shadow-lg rounded-md w-16 h-16">
                            <Image :src="data.image_url" :alt="data.name" />
                        </div>
                        <div class="text-left ml-2">
                            <span class="font-bold">{{ data.name }}</span>
                            <p class="text-sm">{{ data.code }}</p>
                            <p class="text-sm">SKU: {{ data.sku || 'N/A' }}</p>
                        </div>
                    </div>
                </template>
            </Column>
            <Column header="Cantidad">
                <template #body="{data}">
                    <InputNumber v-model="data.quantity" showButtons buttonLayout="vertical"></InputNumber>
                </template>
            </Column>
            <Column header="Precio de compra">
                <template #body="{data}">
                    <div>
                        <InputNumber v-model="data.cost" mode="currency" currency="MXN" :minFractionDigits="2" showButtons></InputNumber>
                    </div>
                </template>
            </Column>
            <Column header="Subtotal">
                <template #body="{data}">
                    {{ formatMoneyNumber(data.cost * data.quantity) }}
                </template>
            </Column>
            <Column header="">
                <template #body="{index}">
                    <Button severity="danger" icon="pi pi-times" @click="removeProduct(index)"></Button>
                </template>
            </Column>
        </DataTable>
    </Card>
</template>