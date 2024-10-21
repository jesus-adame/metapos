<script lang="ts" setup>
import { formatMoneyNumber } from '@/helpers';
import ProductService from '@/Services/ProductService';
import { Product } from '@/types';
import Image from 'primevue/image';
import { useToast } from 'primevue/usetoast';
import { ref } from 'vue';

const searchQuery = ref('');
const filteredProducts = ref<Product[]>([]);
const toast = useToast();
const productService = new ProductService();

const emit = defineEmits(['searched'])

const searchByInput = () => {
    let product = filteredProducts.value[0]

    if (product != null) {
        addSearchedProduct({value: product})
    }
}

const addSearchedProduct = (selected: any) => {
    searchQuery.value = '';
    filteredProducts.value = []
    emit('searched', selected.value);
}

const searchProduct = () => {
    productService.findByCode(searchQuery.value)
    .then(response => {
        if (response.data.length > 0) {
            filteredProducts.value = [...response.data]
        } else {
            toast.add({ severity: 'warn', summary: 'Atención', detail: 'No se encontró el producto', life: 3000 });
        }
    })
}
</script>
<template>
    <form @submit.prevent="searchByInput" class="w-full">
        <AutoComplete
            ref="searchInput"
            :autofocus="true"
            fluid
            :forceSelection="true"
            v-model="searchQuery"
            @complete="searchProduct"
            @item-select="addSearchedProduct"
            option-label="name"
            option-value="code"
            class="w-full"
            :suggestions="filteredProducts"
            placeholder="Producto (F2)">
            <template #option="{option}">
                <div class="flex gap-2 items-center">
                    <Image
                    :src="`${option.image_url}`"
                    :alt="option.image"
                    v-if="option.image"
                    class="min-w-10 max-w-10 max-h-10 text-white shadow-md rounded-md overflow-hidden"
                    preview
                    />
                    <div>
                        <p>{{ option.name }}</p>
                        <p class="text-sm text-gray-500">{{ formatMoneyNumber(option.price * (1 + (option.tax / 100))) }}</p>
                    </div>
                </div>
            </template>
        </AutoComplete>
    </form>
</template>