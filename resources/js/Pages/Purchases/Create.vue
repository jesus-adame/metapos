<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useForm, Head, usePage, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Button from 'primevue/button';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import AutoComplete, { AutoCompleteCompleteEvent } from 'primevue/autocomplete';
import ProductService from '@/Services/ProductService';
import { useToast } from 'primevue/usetoast';
import Card from '@/Components/Card.vue';
import { Product, Supplier } from '@/types';
import Dialog from 'primevue/dialog';
import Payment from './Partials/Payment.vue';
import { formatMoneyNumber } from '@/helpers';
import ProductsList from './Partials/ProductsList.vue';
import SelectProduct from './Partials/SelectProduct.vue';
import SupplierService from '@/Services/SupplierService';
import CreateSupplier from '@/Components/forms/CreateSupplier.vue';

// Retrieve suppliers and products from the server
const page = usePage();
const products = ref<Product[]>([]);
const productService = new ProductService();
const supplierService = new SupplierService();
const searchQuery = ref('');
const toast = useToast();
const selectedSupplier = ref<Supplier>();
const filteredSuppliers = ref<Supplier[]>([]);
const modalPayments = ref(false)
const modalCreateSupplier = ref(false)

// Initialize the form
const form = useForm<{
    supplier_id: number | null,
    products: Product[],
    applicated_at: string | null,
    location_id: number,
}>({
    supplier_id: null,
    products: [],
    applicated_at: null,
    location_id: page.props.location_id,
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
        supplierService.findByCode(event.query.trim())
            .then((response) => {
                filteredSuppliers.value = [...response.data]; // Asegúrate de que response.data es un array
            })
            .catch((error) => {
                console.error('Error fetching Suppliers', error);
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
        sku: product.sku,
        name: product.name,
        image: product.image,
        image_url: product.image_url,
        price: product.price,
        cost: product.cost,
        quantity: 1,
        stock: product.stock,
        tax: product.tax,
        unit_type: product.unit_type,
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
    return form.products.reduce((acc, product) => acc + (product.cost * product.quantity), 0);
});

const clearPurchaseComponent = () => {
    form.products = [];
}

const setSuccessPayment = () => {
    hideModalPayments();
    clearPurchaseComponent();
}

const showModalCreateSupplier = () => {
    modalCreateSupplier.value = true
}

const hideModalCreateSupplier = () => {
    modalCreateSupplier.value = false
}
</script>
<template>
    <Head title="Registrar Compra" />

    <Dialog v-model:visible="modalPayments" modal header="Registrar compra">
        <Payment :form="form" :totalPurchase="totalPurchase" :supplier="selectedSupplier" @cancel="hideModalPayments" @save="setSuccessPayment"></Payment>
    </Dialog>

    <Dialog v-model:visible="modalCreateSupplier" modal header="Registrar proveedor" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <CreateSupplier class="mt-4" @save="hideModalCreateSupplier"></CreateSupplier>
    </Dialog>

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Compra</h2>
        </template>

        <div class="flex flex-wrap gap-2 items-baseline justify-between my-4">
            <div class="search md:w-1/3">
                <form @submit.prevent="addSearchedProduct" class="flex items-center">
                    <IconField iconPosition="left" class="w-full">
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText type="text" v-model="searchQuery" class="mr-2 w-full" placeholder="Producto"/>
                    </IconField>
                    <button type="submit" label="Buscar" class="ml-2"/>
                </form>
            </div>

            <div class="supplier md:w-1/3">
                <div class="flex">
                    <AutoComplete v-model="selectedSupplier" optionLabel="name" :suggestions="filteredSuppliers"
                        @complete="searchSupplier"
                        class="w-full"
                        inputClass="w-full"
                        placeholder="Proveedor" >
                        <template #option="slot">
                            <div class="flex align-options-center">
                                <div>
                                    {{ slot.option.name }} {{ slot.option.lastname }} | {{ slot.option.phone }}
                                </div>
                            </div>
                        </template>
                    </AutoComplete>
                    <Button icon="pi pi-plus" class="ml-2" severity="success" raised @click="showModalCreateSupplier"></Button>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <div id="shoppingTable" class="w-1/3">
                <SelectProduct :products="products" @selected="pushProduct"></SelectProduct>
            </div>

            <div class="w-2/3">
                <ProductsList :products="form.products"></ProductsList>
                <Card width="full" class="mt-5">
                    <div class="text-3xl font-bold flex justify-center w-full mb-5">
                        <span class="mr-4">Saldo a pagar</span>
                        <span>{{ formatMoneyNumber(totalPurchase) }}</span>
                    </div>
                    <div class="flex">
                        <div class="w-1/2 mr-2">
                            <div class="flex justify-center w-full">
                                <Button
                                    :disabled="totalPurchase <= 0"
                                    @click="showModalPayments"
                                    severity="success"
                                    icon="pi pi-dollar"
                                    type="submit"
                                    label="Pagar"
                                    class="w-full text-xl uppercase"/>
                            </div>
                        </div>
                        <div class="w-1/2">
                            <Link :href="route('purchases.index')" class="flex justify-center w-full">
                                <Button class="w-full text-xl uppercase" label="Ver compras" severity="info"></Button>
                            </Link>
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </UserLayout>
</template>
