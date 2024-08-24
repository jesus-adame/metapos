<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import ProductService from '@/Services/ProductService';
import CustomerService from "@/Services/CustomerService";
import Card from '@/Components/Card.vue';
import { useToast } from 'primevue/usetoast';
import Button from 'primevue/button';
import IconField from 'primevue/iconfield';
import Dialog from 'primevue/dialog';
import { formatMoneyNumber, getPercentage, percentageNumber } from '@/helpers';
import ProductsList from './Partials/ProductsList.vue';
import Payment from './Partials/Payment.vue';
import CreateCashMovement from '@/Components/forms/CreateCashMovement.vue';
import { Customer, Product } from '@/types';
import { AutoCompleteCompleteEvent } from 'primevue/autocomplete';
import SelectProduct from '@/Components/grids/SelectProduct.vue';
import Discount from './Partials/Discount.vue';
import CreateCustomer from '@/Components/forms/CreateCustomer.vue';

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
const modalDiscount = ref(false)
const modalCreateCustomer = ref(false)

// Initialize the form
const form = reactive<{
  customer_id: number | null;
  products: Product[];
  discount: {
    type: string,
    amount: number
  } | null;
}>({
    customer_id: null,
    products: [],
    discount: null,
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
    if (product.stock <= 0) {
        toast.add({ severity: 'warn', summary: 'Atención', detail: 'Producto sin existencias', life: 3000 });
        return;
    }

    if (form.products.length > 0) {
        const currentProduct = form.products.find((element) => element.code == product.code);

        if (currentProduct !== undefined) {
            if (currentProduct.quantity >= product.stock) {
                toast.add({ severity: 'warn', summary: 'Atención', detail: 'Producto sin existencias', life: 3000 });
                return;
            }
            currentProduct.quantity++
            toast.add({ severity: 'success', summary: 'Correcto', detail: 'Producto agregado correctamente', life: 1100 });
            return;
        }
    }

    form.products.push({ ...product, quantity: 1 })
}

const totalSale = computed(() => {
    let subtotal = form.products.reduce((acc, product) => acc + (product.price * product.quantity), 0)
    if (form.discount == null) {
        return subtotal
    }

    if (form.discount.type == 'amount')
        return subtotal - form.discount.amount;
    return subtotal - getPercentage(subtotal, form.discount.amount)
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

const fetchProducts = () => {
    productService.fetchAll()
    .then(response => {
        const paginate = response.data
        products.value = [...paginate.data]
    });
}

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

const showModalDiscount = () => {
    modalDiscount.value = true
}

const hideModalDiscount = () => {
    modalDiscount.value = false
}
const showModalCreateCustomer = () => {
    modalCreateCustomer.value = true
}

const hideModalCreateCustomer = () => {
    modalCreateCustomer.value = false
}

const setSuccessPayment = () => {
    hideModalPayments();
    clearSaleComponent();
    removeDiscount();
    fetchProducts();
}

const setSuccessDiscount = (amount: number, type: string) => {
    hideModalDiscount();
    form.discount = {
        type: type,
        amount: amount
    }
}

const removeDiscount = () => {
    form.discount = null
}

const formatDiscount = computed(() => {
    if (form.discount) {
        if (form.discount.type == 'amount')
            return formatMoneyNumber(form.discount.amount)
        if (form.discount.type == 'percentage')
            return percentageNumber(form.discount.amount)
    } else {
        return ''
    }
})

onMounted(() => {
    fetchProducts()
});
</script>

<template>
    <Head title="Punto de Venta" />

    <Dialog v-model:visible="modalDiscount" modal header="Agregar Descuento" :style="{ width: '35rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <Discount :totalSale="totalSale" :form="form" @cancel="hideModalDiscount" @apply="setSuccessDiscount"></Discount>
    </Dialog>

    <Dialog v-model:visible="modalPayments" modal header="Agregar Pago" :style="{ width: '35rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <Payment :totalSale="totalSale" :form="form" @cancel="hideModalPayments" @save="setSuccessPayment"></Payment>
    </Dialog>

    <Dialog v-model:visible="modalCashMovements" modal header="Registrar movimiento" :style="{ width: '35rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <CreateCashMovement class="mt-2">
            <Button label="Cancelar" @click="hideModalMovements" class="ml-2"></Button>
        </CreateCashMovement>
    </Dialog>

    <Dialog v-model:visible="modalCreateCustomer" modal header="Registrar cliente" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <CreateCustomer class="mt-4" @save="hideModalCreateCustomer"></CreateCustomer>
    </Dialog>

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">POS</h2>
        </template>

        <div class="flex flex-wrap gap-2 items-baseline justify-between mb-4 mt-4 w-full">
            <div class="search w-full md:w-1/3">
                <form @submit.prevent="addSearchedProduct" class="flex items-center">
                    <IconField iconPosition="left" class="w-full">
                        <InputIcon class="pi pi-search"></InputIcon>
                        <InputText type="text" v-model="searchQuery" class="w-full" placeholder="Producto" autofocus/>
                    </IconField>
                </form>
            </div>
            <div class="customer w-full md:w-1/2">
                <div class="flex justify-end w-full">
                    <Button v-if="form.discount == null" label="Descuento" class="mr-2" icon="pi pi-tag" @click="showModalDiscount"></Button>
                    <div class="w-1/2">
                        <div class="flex">
                            <AutoComplete v-model="selectedCustomer" optionLabel="name" :suggestions="filteredCustomers"
                                @complete="searchCustomer"
                                @change="setCustomer"
                                class="w-full"
                                inputClass="w-full"
                                placeholder="Cliente">
                                <template #option="slot">
                                    <div class="flex align-options-center">
                                        <div>
                                            {{ slot.option.name }} {{ slot.option.lastname }} | {{ slot.option.phone }}
                                        </div>
                                    </div>
                                </template>
                            </AutoComplete>
                            <Button v-tooltip.right="'Nuevo cliente'" icon="pi pi-plus" class="ml-2" severity="success" raised @click="showModalCreateCustomer"></Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <SelectProduct :products="products" @selected="pushProduct"></SelectProduct>
            <div>
                <ProductsList :products="form.products"></ProductsList>

                <Card width="full" class="mt-4">
                    <div v-if="form.discount" class="text-xl text-gray-500 font-bold flex justify-end items-center cursor-pointer mb-2" @click="removeDiscount">
                        <span class="mr-2">
                            <i class="pi pi-tag"></i>
                        </span>
                        <p>Descuento {{ formatDiscount }}</p>
                    </div>
                    <div class="text-3xl font-bold text-gray-700 text-right w-full mb-5">
                        <p>Saldo {{ formatMoneyNumber(totalSale) }}</p>
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
                        <Button @click="showModalMovements" label="Entrada / Salida" severity="danger" class="w-1/2 text-xl uppercase mr-2" icon="pi pi-arrow-right-arrow-left"></Button>
                        <Link :href="route('cash-flows.index')" class="d-block w-1/2">
                            <Button label="Ver de caja" severity="warn" class="w-full text-xl uppercase" icon="pi pi-inbox"></Button>
                        </Link>
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