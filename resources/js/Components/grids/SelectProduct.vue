<script lang="ts" setup>
import { formatMoneyNumber } from '@/helpers';
import { Product } from '@/types';

defineProps<{
    products: Product[]
}>()

const emit = defineEmits(['selected'])

const pushProduct = (product: Product) => {
    emit('selected', product)
}
</script>
<template>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 justify-start">
        <div v-for="(product, index) in products" :key="index">
            <div @click="pushProduct(product)" class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-gray-900 cursor-pointer">
                <Image v-if="product.image" :src="product.image_url" :alt="product.name" class="shadow-lg rounded-md overflow-hidden" width="100%" :pt="{root: 'w-full'}" />
                <div class="py-2 px-4">
                    <p class="font-bold">{{ product.name }}</p>
                    <p class="text-sm">SKU: {{ product.sku || 'N/A' }}</p>
                    {{ formatMoneyNumber(product.price) }}
                </div>
            </div>
        </div>
    </div>
</template>