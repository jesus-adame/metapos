<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import AutoComplete, { AutoCompleteCompleteEvent } from 'primevue/autocomplete';
import ProductService from '@/Services/ProductService';
import CustomerService from "@/Services/CustomerService";
import { useToast } from 'primevue/usetoast';
import Card from '@/Components/Card.vue';
import ProductsList from '../Sales/Partials/ProductsList.vue';
import SelectProduct from '@/Components/grids/SelectProduct.vue';
import { Product, Supplier } from '@/types';
import Dialog from 'primevue/dialog';
import Payment from './Partials/Payment.vue';
import { formatMoneyNumber } from '@/helpers';

// Retrieve suppliers and products from the server
const products = ref<Product[]>([]);

const productService = new ProductService();
const customerService = new CustomerService();
const searchQuery = ref('');
const toast = useToast();
const selectedSupplier = ref<Supplier>();
const filteredSuppliers = ref<Supplier[]>([]);
const modalPayments = ref(false)

// Initialize the form
const form = useForm<{
    supplier_id: number | null; // Assuming customer_id is a number
    products: Product[];
}>({
    supplier_id: null,
    products: [],
});

// Add a product to the form
const addSearchedProduct = () => {
    productService.findByCode(searchQuery.value)
    .then(response => {
        searchQuery.value = '';

        if (response.data.length > 0) {
            const product = response.data[0];
            toast.add({ severity: 'success', summary: 'Correcto', detail: 'Producto agregado correctamente', life: 1100 });

            if (form.products.length > 0) {
                const productIndex = form.products.findIndex((element) => element.code == product.code);

                if (productIndex > -1) {
                    form.products[productIndex].quantity++
                    return;
                }
            }
            pushProduct(product)
        }
    })
}

const searchSupplier = (event: AutoCompleteCompleteEvent) => {
    setTimeout(() => {
        customerService.findByCode(event.query.trim())
            .then((response) => {
                filteredSuppliers.value = [...response.data]; // Asegúrate de que response.data es un array
            })
            .catch((error) => {
                console.error('Error fetching customers', error);
            });
    }, 250);
}

const pushProduct = (product: Product) => {
    toast.add({ severity: 'success', summary: 'Correcto', detail: 'Producto agregado correctamente', life: 1100 });

    if (form.products.length > 0) {
        const productIndex = form.products.findIndex((element) => element.code == product.code);

        if (productIndex > -1) {
            form.products[productIndex].quantity++
            return;
        }
    }

    form.products.push({
        id: product?.id,
        code: product.code,
        name: product.name,
        image: product.image,
        image_url: product.image_url,
        price: product.price,
        quantity: 1,
        stock: product.stock
    })
}

const showModalPayments = () => {
    modalPayments.value = true
}

const hideModalPayments = () => {
    modalPayments.value = false
}

onMounted(() => {
    productService.fetchAll()
    .then(response => {
        const paginate = response.data
        products.value = [...paginate.data]
    });
});

const totalPurchase = computed(() => {
    return form.products.reduce((acc, product) => acc + (product.price * product.quantity), 0);
});
</script>
<template>
    <Head title="Registrar Compra" />

    <Dialog v-model:visible="modalPayments">
        <Payment :form="form" :totalPurchase="totalPurchase"></Payment>
    </Dialog>

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Compra</h2>
        </template>

        <div class="flex items-baseline justify-between my-4">
            <div class="search w-1/3">
                <form @submit.prevent="addSearchedProduct" class="flex items-center">
                    <IconField iconPosition="left" class="w-full">
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText type="text" v-model="searchQuery" class="mr-2" placeholder="Producto"/>
                    </IconField>
                    <button type="submit" label="Buscar" class="ml-2"/>
                </form>
            </div>

            <div class="customer w-1/3">
                <AutoComplete v-model="selectedSupplier" optionLabel="first_name" :suggestions="filteredSuppliers"
                    @complete="searchSupplier"
                    class="w-full"
                    inputClass="w-full"
                    placeholder="Proveedor" dropdown >
                    <template #option="slot">
                        <div class="flex align-options-center">
                            <div>
                                {{ slot.option.first_name }} {{ slot.option.last_name }} | {{
                                    slot.option.phone }}
                            </div>
                        </div>
                    </template>
                </AutoComplete>
            </div>
        </div>

        <div class="flex">
            <div id="shoppingTable" class="w-1/2 mr-2">
                <SelectProduct :products="products" @selected="pushProduct"></SelectProduct>
            </div>

            <div class="w-1/2">
                <ProductsList :products="form.products"></ProductsList>
                <Card width="full" class="mt-5">
                    <div class="text-3xl font-bold flex justify-center w-full mb-5">
                        <span class="mr-4">Saldo a pagar</span>
                        <span>{{ formatMoneyNumber(totalPurchase) }}</span>
                    </div>
                    <div class="flex justify-center w-full">
                        <Button
                            @click="showModalPayments"
                            severity="success"
                            icon="pi pi-dollar"
                            type="submit"
                            label="Pagar"
                            class="w-full text-xl uppercase"/>
                    </div>
                </Card>
            </div>
        </div>
    </UserLayout>
</template>
