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
                <template #body="slot">
                    <div class="flex">
                        <div v-if="slot.data.image" class="overflow-hidden hidden lg:block shadow-lg rounded-md w-20 h-20">
                            <Image :src="slot.data.image_url" :alt="slot.data.name" />
                        </div>
                        <div class="text-left ml-2">
                            <span class="font-bold">{{ slot.data.name }}</span>
                            <p class="text-sm">{{ slot.data.code }}</p>
                            <p class="text-sm">SKU: {{ slot.data.sku || 'N/A' }}</p>
                        </div>
                    </div>
                </template>
            </Column>
            <Column header="Cantidad">
                <template #body="slot">
                    <InputNumber v-model="slot.data.quantity" showButtons buttonLayout="vertical"></InputNumber>
                </template>
            </Column>
            <Column header="Precio de compra">
                <template #body="slot">
                    <div>
                        <InputNumber v-model="slot.data.cost" mode="currency" currency="MXN" :minFractionDigits="2" showButtons></InputNumber>
                    </div>
                </template>
            </Column>
            <Column header="Subtotal">
                <template #body="slot">
                    {{ formatMoneyNumber(slot.data.cost * slot.data.quantity) }}
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