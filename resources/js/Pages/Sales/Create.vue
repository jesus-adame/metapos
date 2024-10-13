<script setup lang="ts">
import { computed, onMounted, onUnmounted, reactive, ref, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import ProductService from '@/Services/ProductService';
import CustomerService from "@/Services/CustomerService";
import { useToast } from 'primevue/usetoast';
import Button from 'primevue/button';
import IconField from 'primevue/iconfield';
import Dialog from 'primevue/dialog';
import { formatMoneyNumber, getPercentage, percentageNumber, roundBank } from '@/helpers';
import ProductsList from './Partials/ProductsList.vue';
import Payment from './Partials/Payment.vue';
import CreateCashMovement from '@/Components/forms/CreateCashMovement.vue';
import { CartItem, Customer, Product } from '@/types';
import { AutoCompleteCompleteEvent } from 'primevue/autocomplete';
import SelectProduct from '@/Components/grids/SelectProduct.vue';
import Discount from './Partials/Discount.vue';
import CreateCustomer from '@/Components/forms/CreateCustomer.vue';
import UserIcon from '@/Components/icons/UserIcon.vue';
import Mousetrap from 'mousetrap';
import 'mousetrap-global-bind';
import Image from 'primevue/image';

// Retrieve customers and products from the server
const productService = new ProductService();
const customerService = new CustomerService();
const searchQuery = ref('');
const toast = useToast();
const selectedCustomer = ref();
const filteredCustomers = ref<Customer[]>([]);
const modalPayments = ref(false)
const modalCashMovements = ref(false)
const modalDiscount = ref(false)
const modalCreateCustomer = ref(false)
const filteredProducts = ref<Product[]>([]);

// Inputs for shortcuts
const searchInput = ref();
const customerInput = ref();
const paymentButton = ref();

// Initialize the form
const form = reactive<{
  customer_id: number | null;
  products: CartItem[];
  wholesale: boolean;
  discount: {
    type: string,
    amount: number
  } | null;
}>({
    customer_id: null,
    products: [],
    discount: null,
    wholesale: false,
});

const addSearchedProduct = (selected: any) => {
    console.log(selected.value);
    searchQuery.value = '';
    pushProduct(selected.value)
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

    let cartItem: CartItem = {
        id: product.id,
        name: product.name,
        code: product.code,
        sku: product.sku,
        price: roundBank(product.price + getPercentage(product.price, product.tax)), // Con impuestos
        image: product.image,
        image_url: product.image_url,
        quantity: 1,
        stock: product.stock,
        unit_type: product.unit_type,
        tax: product.tax ?? 0
    };

    if (form.wholesale && product?.wholesale_price) {
        cartItem.price = roundBank(product.wholesale_price + getPercentage(product.wholesale_price, product.tax))
    }

    form.products.push(cartItem)
    toast.add({ severity: 'success', summary: 'Correcto', detail: 'Producto agregado correctamente', life: 1100 });
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

watch(() => form.wholesale, () => {
    clearSaleComponent()
})

onMounted(() => {
    Mousetrap.bindGlobal('f3', (e: Event) => {
        e.preventDefault()
        if (paymentButton.value) {
            paymentButton.value.$el.click()
        }
    })

    Mousetrap.bindGlobal(['ctrl+a', 'f2'], (e: Event) => {
        e.preventDefault()

        if (searchInput.value) {
            searchInput.value.rootEl.firstChild.focus()
        }
    })

    Mousetrap.bindGlobal('ctrl+c', (e: Event) => {
        e.preventDefault()
        if (customerInput.value) {
            customerInput.value.$el.querySelector('input').focus()
        }
    })
})

onUnmounted(() => {
    Mousetrap.reset()
})
</script>

<template>
    <Head title="Punto de Venta" />

    <Dialog v-model:visible="modalDiscount" modal header="Descuento" :style="{ width: '35rem' }">
        <Discount :totalSale="totalSale" :form="form" @cancel="hideModalDiscount" @apply="setSuccessDiscount"></Discount>
    </Dialog>

    <Dialog v-model:visible="modalPayments" modal header="Agregar Pago" :style="{ width: '35rem' }">
        <Payment :totalSale="totalSale" :form="form" @cancel="hideModalPayments" @save="setSuccessPayment"></Payment>
    </Dialog>

    <Dialog v-model:visible="modalCashMovements" modal header="Registrar movimiento" :style="{ width: '35rem' }">
        <CreateCashMovement>
            <Button label="Cancelar" @click="hideModalMovements" class="ml-2" raised></Button>
        </CreateCashMovement>
    </Dialog>

    <Dialog v-model:visible="modalCreateCustomer" modal header="Nuevo cliente" :style="{ width: '35rem' }">
        <CreateCustomer @save="hideModalCreateCustomer"></CreateCustomer>
    </Dialog>

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">POS</h2>
        </template>

        <div class="flex flex-wrap gap-2 items-baseline justify-between mb-3 mt-3 w-full">
            <div class="search w-full md:w-1/3">
                <div class="flex items-center">
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
                </div>
            </div>
            <div class="customer w-full md:w-1/2">
                <div class="flex gap-2 justify-end w-full">
                    <Button raised v-if="form.discount == null" label="Descuento" rounded icon="pi pi-tag" @click="showModalDiscount"></Button>
                    <div class="flex items-center gap-2 px-1 text-gray-800">
                        <ToggleSwitch v-model="form.wholesale" inputId="wholesale" />
                        <label for="wholesale">Mayorista</label>
                    </div>
                    <div v-if="!selectedCustomer?.name" class="flex gap-1">
                        <AutoComplete v-model="selectedCustomer" optionLabel="name" :suggestions="filteredCustomers"
                            @complete="searchCustomer"
                            @change="setCustomer"
                            ref="customerInput"
                            class="w-full"
                            inputClass="w-full"
                            placeholder="Cliente (ctrl+C)">
                            <template #option="{option}">
                                <div class="flex align-items-center">
                                    <div>
                                        {{ option.name }} {{ option.lastname }} | {{ option.phone }}
                                    </div>
                                </div>
                            </template>
                        </AutoComplete>
                        <Button v-tooltip.right="'Nuevo cliente'" icon="pi pi-plus" severity="success" raised @click="showModalCreateCustomer"></Button>
                    </div>
                    <div v-if="selectedCustomer?.name" class="flex items-center justify-end font-bold cursor-pointer px-1" @click="selectedCustomer = ''">
                        <UserIcon v-tooltip="'Cliente'">
                            {{ selectedCustomer.name }} {{ selectedCustomer.lastname }}
                        </UserIcon>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <div>
                <SelectProduct @selected="pushProduct"></SelectProduct>
            </div>
            <div class="flex flex-col gap-2">
                <ProductsList :products="form.products"></ProductsList>
                <div class="bg-white p-4 shadow-md rounded">
                    <div class="flex justify-end w-full">
                        <div
                            v-if="form.discount"
                            class="text-xl text-gray-500 font-bold flex gap-2 p-2 justify-end items-center cursor-pointer"
                            @click="removeDiscount"
                            v-tooltip.top="'Eliminar descuento'"
                        >
                            <span>
                                <i class="pi pi-tag"></i>
                            </span>
                            <p>Descuento {{ formatDiscount }}</p>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-700 text-right w-full mb-5">
                        <p>Saldo {{ formatMoneyNumber(totalSale) }}</p>
                    </div>
                    <div class="flex justify-center w-full">
                        <Button
                            raised
                            :disabled="totalSale <= 0"
                            @click="showModalPayments"
                            ref="paymentButton"
                            severity="success"
                            icon="pi pi-dollar"
                            type="submit"
                            label="Pagar (F3)"
                            class="w-full text-xl"/>
                    </div>
                    <div class="flex mt-3 w-full">
                        <Button raised @click="showModalMovements" label="Entrada / Salida" severity="danger" class="w-1/2 mr-2" icon="pi pi-arrow-right-arrow-left"></Button>
                        <Link :href="route('cash-flows.index')" class="d-block w-1/2">
                            <Button raised label="Ver de caja" severity="warn" class="w-full" icon="pi pi-inbox"></Button>
                        </Link>
                    </div>
                </div>
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