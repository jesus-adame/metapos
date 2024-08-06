<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import ProductService from '@/Services/ProductService';
import CustomerService from "@/Services/CustomerService";
import Card from '@/Components/Card.vue';
import { useToast } from 'primevue/usetoast';
import Button from 'primevue/button';
import IconField from 'primevue/iconfield';
import Dialog from 'primevue/dialog';
import { formatMoneyNumber } from '@/helpers';
import ProductsList from './Partials/ProductsList.vue';
import Payment from './Partials/Payment.vue';
import CreateCashMovement from '@/Components/forms/CreateCashMovement.vue';
import { Customer, Product } from '@/types';
import { AutoCompleteCompleteEvent } from 'primevue/autocomplete';
import Image from 'primevue/image';

// Retrieve customers and products from the server
const productService = new ProductService();
const customerService = new CustomerService();
const searchQuery = ref('');
const toast = useToast();
const selectedCustomer = ref();
const filteredCustomers = ref<Customer[]>([]);
const products = ref<Product[]>([]);
const modalPayments = ref(false)
const modalCashMovements = ref(false)

// Initialize the form
const form = useForm<{
  customer_id: number | null;
  products: Product[];
}>({
    customer_id: null,
    products: [],
});

const addSearchedProduct = () => {
    productService.findByCode(searchQuery.value)
    .then(response => {
        searchQuery.value = '';

        if (response.data.length > 0) {
            const product = response.data[0];
            pushProduct(product);
        }
    })
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

const totalSale = computed(() => {
    return form.products.reduce((acc, product) => acc + (product.price * product.quantity), 0);
});

const searchCustomer = (event: AutoCompleteCompleteEvent) => {
    setTimeout(() => {
        customerService.findByCode(event.query.trim())
            .then((response) => {
                filteredCustomers.value = [...response.data]; // Asegúrate de que response.data es un array
            })
            .catch((error) => {
                console.error('Error fetching customers', error);
            });
    }, 250);
}

const clearSaleComponent = () => {
    form.products = [];
}

onMounted(() => {
    productService.fetchAll()
    .then(response => {
        const paginate = response.data
        products.value = [...paginate.data]
    });
});

const setCustomer = () => {
    form.customer_id = selectedCustomer.value?.id
}

const showModalPayments = () => {
    modalPayments.value = true
}

const hideModalPayments = () => {
    modalPayments.value = false
}

const showModalMovements = () => {
    modalCashMovements.value = true
}

const hideModalMovements = () => {
    modalCashMovements.value = false
}

const setSuccessPayment = () => {
    hideModalPayments();
    clearSaleComponent();
    toast.add({ severity: 'success', summary: 'Correcto', detail: 'Venta saldada correctamente', life: 1200 });
}
</script>

<template>
    <Head title="Register Sale" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">POS</h2>
        </template>

        <div class="mt-5"></div>

        <Dialog v-model:visible="modalPayments" modal header="Agregar Pago" :style="{ width: '35rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <Payment :totalSale="totalSale" :form="form" @cancel="hideModalPayments" @save="setSuccessPayment"></Payment>
        </Dialog>

        <Dialog v-model:visible="modalCashMovements" modal header="Registrar movimiento" :style="{ width: '35rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <CreateCashMovement class="mt-2">
                <Button label="Cancelar" @click="hideModalMovements" class="ml-2"></Button>
            </CreateCashMovement>
        </Dialog>

        <div class="flex items-baseline justify-between mb-4">
            <div class="search w-1/3">
                <form @submit.prevent="addSearchedProduct" class="flex items-center">
                    <IconField iconPosition="left" class="w-full">
                        <InputIcon class="pi pi-search"></InputIcon>
                        <InputText type="text" v-model="searchQuery" class="w-full" placeholder="Producto" autofocus/>
                    </IconField>
                </form>
            </div>

            <div class="customer w-1/3">
                <AutoComplete v-model="selectedCustomer" optionLabel="first_name" :suggestions="filteredCustomers"
                    @complete="searchCustomer"
                    @change="setCustomer"
                    class="w-full"
                    inputClass="w-full"
                    placeholder="Cliente">
                    <template #option="slot">
                        <div class="flex align-options-center">
                            <div>
                                {{ slot.option.first_name }} {{ slot.option.last_name }} | {{ slot.option.phone }}
                            </div>
                        </div>
                    </template>
                </AutoComplete>
            </div>
        </div>

        <div class="flex">
            <div id="shoppingTable" class="w-1/2 mr-2">
                <div class="flex flex-wrap justify-start">
                    <div class="px-1 w-1/4" v-for="(product, index) in products" :key="index">
                        <div @click="pushProduct(product)" class="bg-white overflow-hidden shadow-sm sm:rounded-lg text-gray-900 cursor-pointer">
                            <Image :src="product.image_url" :alt="product.name" class="shadow-lg rounded-md overflow-hidden" width="100%" :pt="{root: 'w-full'}" />
                            <div class="py-2 px-4">
                                <p class="font-bold">{{ product.name }}</p>
                                {{ formatMoneyNumber(product.price) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-1/2">
                <ProductsList :products="form.products"></ProductsList>

                <Card width="full" class="mt-5">
                    <div class="text-3xl font-bold flex justify-center w-full mb-5">
                        <span class="mr-4">Saldo a pagar</span>
                        <span>{{ formatMoneyNumber(totalSale) }}</span>
                    </div>
                    <div class="flex justify-center w-full">
                        <Button
                            :disabled="totalSale <= 0"
                            @click="showModalPayments"
                            severity="success"
                            icon="pi pi-dollar"
                            type="submit"
                            label="Pagar"
                            class="w-full text-xl uppercase"/>
                    </div>
                    <div class="flex mt-4 w-full">
                        <Button @click="showModalMovements" label="Movimiento de caja" severity="warn" class="w-full text-xl uppercase" icon="pi pi-arrow-right-arrow-left"></Button>
                    </div>
                </Card>
            </div>
        </div>
    </UserLayout>
</template>

<style lang="scss" scoped>
.fullscreen-wrapper {
  width: 100%;
  height: 100%;
  background: #333;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 20px;

  .button {
    margin-bottom: 20px;
  }
}
</style>