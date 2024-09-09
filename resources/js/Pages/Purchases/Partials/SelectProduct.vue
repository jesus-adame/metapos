<script lang="ts" setup>
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
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
        <div v-for="(product, index) in products" :key="index">
            <div @click="pushProduct(product)" class="transition-all bg-white relative hover:shadow-lg overflow-hidden shadow-sm sm:rounded-lg text-gray-700 cursor-pointer">
                <div v-if="product.image" class="h-40 overflow-hidden">
                    <Image :src="product.image_url" :alt="product.name" :pt="{root: 'w-full'}" />
                </div>
                <div class="py-2 px-4">
                    <p class="font-bold">{{ product.name }}</p>
                    <p class="text-sm">SKU: {{ product.sku || 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</template>