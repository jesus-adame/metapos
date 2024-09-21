<script lang="ts" setup>
import ProductService from '@/Services/ProductService';
import { useCategoryStore } from '@/stores/CategoryStore';
import { Product } from '@/types';
import { onMounted, ref } from 'vue';

const products = ref<Product[]>([]);
const productService = new ProductService();
const categoriesStore = useCategoryStore()
const selectedCategory = ref<string | null>(null)

const emit = defineEmits(['selected'])

const pushProduct = (product: Product) => {
    emit('selected', product)
}

const fetchProducts = () => {
    selectedCategory.value = null
    productService.paginate(1, 50)
    .then(response => {
        const paginate = response.data
        products.value = [...paginate.data]
    });
}

const filterProducts = (category: string) => {
    selectedCategory.value = category
    productService.findByCategory(category)
    .then(response => {
        const paginate = response.data
        products.value = [...paginate.data]
    });
}

onMounted(() => {
    fetchProducts();
    categoriesStore.fetchItems()
})
</script>
<template>
    <div class="flex items-center gap-1 font-medium text-gray-500 pb-2 mb-2 overflow-auto w-full text-nowrap">
        <div v-if="selectedCategory"
            @click="fetchProducts"
            class="py-2 px-5 shadow rounded-sm bg-white cursor-pointer hover:bg-gray-50">Todos los productos</div>
        <div v-for="(item, index) in categoriesStore.categories" :key="index"
            @click="filterProducts(item.name)"
            :class="{ '!bg-blue-50 !text-blue-500': selectedCategory == item.name }"
            class="py-2 px-5 shadow rounded-sm bg-white cursor-pointer hover:bg-gray-50">
            {{ item.name }}
        </div>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
        <div v-for="(product, index) in products" :key="index">
            <div @click="pushProduct(product)" class="transition-all bg-white relative hover:shadow-lg overflow-hidden shadow-sm sm:rounded-lg text-gray-700 cursor-pointer">
                <div v-if="product.image" class="h-32 overflow-hidden">
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