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
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
        <div v-for="(product, index) in products" :key="index">
            <div @click="pushProduct(product)" class="transition-all bg-white relative hover:shadow-lg overflow-hidden shadow-sm sm:rounded-lg text-gray-700 cursor-pointer">
                <div v-if="product.image" class="h-36 overflow-hidden">
                    <Image :src="product.image_url" :alt="product.name" :pt="{root: 'w-full'}" />
                </div>
                <div class="py-2 px-4">
                    <p class="font-bold">{{ product.name }}</p>
                    <p class="text-sm">{{ product.code }}</p>
                    <p class="text-sm">SKU: {{ product.sku || 'N/A' }}</p>
                </div>
                <div v-if="product.stock <= 0" class="absolute top-1 right-1 shadow-md bg-red-500 rounded-full px-3 text-sm text-red-50 text-center flex justify-around items-center">
                    <i class="pi pi-info-circle mr-2"></i>
                    <p>Sin stock</p>
                </div>
            </div>
        </div>
    </div>
</template>