<script lang="ts" setup>
import { can, formatMoneyNumber, percentageNumber } from '@/helpers';
import { Product } from '@/types';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable, { DataTablePageEvent } from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Image from 'primevue/image';
import Tag from 'primevue/tag';
import { onMounted, ref, watch } from 'vue';
import CreateProduct from '../forms/CreateProduct.vue';
import ProductService from '@/Services/ProductService';
import axios, { AxiosResponse } from 'axios';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import EditProduct from '../forms/EditProduct.vue';
import { Link } from '@inertiajs/vue3';
import { useAuthStore } from '@/stores/AuthStore';
import PDFObject from '../PDFObject.vue';
import InputNumber from 'primevue/inputnumber';
import PrintTicketButton from '../prints/PrintTicketButton.vue';

const createModal = ref<Boolean>(false)
const editModal = ref<Boolean>(false)
const barcodeModal = ref<Boolean>(false)
const toast = useToast()
const confirm = useConfirm()
const productService = new ProductService()
const product = ref<Product | null>(null)
const items = ref<Product[]>([])
const rows = ref<number>(10)
const current_page = ref(1)
const totalRecords = ref(0)
const authStore = useAuthStore()
const quantity = ref(1)
const printer = ref(false)

const getSeverity = (product: Product) => {
    switch (product.stock > 0) {
        case true:
            return 'success';
        case false:
            return 'danger';
        default:
            return null;
    }
};

const openModalCreate = () => {
    createModal.value = true
}

const closeModalCreate = () => {
    createModal.value = false
    fetchItems(current_page.value)
}

const openModalEdit = (productData: Product) => {
    product.value = productData
    editModal.value = true
}

const closeModalEdit = () => {
    editModal.value = false
    fetchItems(current_page.value)
}

const openModalBarcode = (productData: Product) => {
    product.value = productData
    barcodeModal.value = true
    quantity.value = 1
}

const fetchItems = (pageNumber: number) => {
    productService.paginate(pageNumber, rows.value)
    .then((response: AxiosResponse) => {
        const paginate = response.data

        totalRecords.value = paginate.total
        items.value = paginate.data
    })
}

onMounted(() => {
    fetchItems(current_page.value)
})

const deleteItem = (url: string) => {
    axios.post(url, { _method: 'delete' })
    .then((response: AxiosResponse) => {
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 1500 })
        fetchItems(current_page.value)
    })
    .catch(reject => {
        console.error(reject.response.data.errors);
        toast.add({ summary: 'Error', detail: reject.response.data.message, severity: 'error', life: 3000 })
    })
}

const confirmDelete = (url: string) => {
    confirm.require({
        header: '¿Está seguro?',
        message: 'Se eliminará el producto con su inventario',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancelar',
            severity: 'secondary',
        },
        acceptProps: {
            label: 'Eliminar',
            severity: 'danger'
        },
        accept: () => {
            deleteItem(url)
        }
    });
}

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchItems(pageNumber);
}

watch(() => authStore.cashRegister, () => {
    fetchItems(current_page.value)
})

const openLabels = () => {
    printer.value = true
    barcodeModal.value = false
}
</script>
<template>
    <Dialog v-model:visible="createModal" header="Nuevo producto" :modal="true" :style="{ width: '65rem' }">
        <CreateProduct @save="closeModalCreate"></CreateProduct>
    </Dialog>

    <Dialog v-model:visible="editModal" header="Editar producto" :modal="true" @hide="fetchItems(current_page)" :style="{ width: '65rem' }">
        <EditProduct @save="closeModalEdit" :product="product ?? undefined"></EditProduct>
    </Dialog>

    <Dialog v-model:visible="barcodeModal" header="Código de barras" :modal="true">
        <div class="grid gap-4">
            <div>
                <span>Número de etiquetas</span>
                <div class="flex gap-2">
                    <InputNumber v-model="quantity" class="w-full"></InputNumber>
                    <PrintTicketButton :pdfUrl="route('barcodes.show', {productId: product?.id, quantity: quantity})"></PrintTicketButton>
                </div>
            </div>
            <Button :disabled="!quantity" label="Descargar" @click="openLabels"></Button>
        </div>
    </Dialog>

    <Dialog v-model:visible="printer" header="Código de barras" :modal="true">
        <PDFObject :url="route('barcodes.show', {productId: product?.id, quantity: quantity})" :options="{ height: '100vh', width: '30vw', border: '1px', solid: '#ccc' }" />
    </Dialog>

    <DataTable :value="items" paginator :rows="rows" @page="onPage" :totalRecords="totalRecords" lazy>
        <Column field="id" header="#"></Column>
        <Column field="name" header="Producto">
            <template #body="{data}">
                <div class="flex items-center">
                    <Image
                        :src="`${data.image_url}`"
                        :alt="data.image"
                        v-if="data.image"
                        class="w-16 h-16 text-white shadow-md mr-8 rounded-md overflow-hidden"
                        preview
                    />
                    <div>
                        <Link :href="route('products.edit', {product: data.id})">
                            <p class="text-lg font-semibold">{{ data.name }}</p>
                        </Link>
                        <p class="text-sm">Código: {{ data.code }}</p>
                        <p class="text-sm">SKU: {{ data.sku || 'N/A' }}</p>
                    </div>
                </div>
            </template>
        </Column>
        <Column field="cost" header="">
            <template #header>
                <div class="w-full text-center">
                    Precio de compra
                </div>
            </template>
            <template #body="{data}">
                <div class="w-full text-center">
                    {{ formatMoneyNumber(data.cost) }}
                </div>
            </template>
        </Column>
        <Column field="price" header="">
            <template #header>
                <div class="w-full text-center">
                    Precio de venta
                </div>
            </template>
            <template #body="{data}">
                <div class="w-full text-center">
                    {{ formatMoneyNumber(data.price * (1 + (data.tax / 100))) }}
                </div>
            </template>
        </Column>
        <Column field="price" header="">
            <template #header>
                <div class="w-full text-center">
                    IVA
                </div>
            </template>
            <template #body="{data}">
                <div class="w-full text-center">
                    {{ percentageNumber(data.tax ?? 0) }}
                </div>
            </template>
        </Column>
        <Column header="">
            <template #header>
                <div class="w-full text-center">
                    Inventario
                </div>
            </template>
            <template #body="{data}">
                <div class="text-center w-full">
                    <span class="font-bold">
                        {{ data.stock || 0 }}
                    </span>
                </div>
            </template>
        </Column>
        <Column field="unit" header="Unidad">
            <template #body="{data}">
                {{ data.unit_type }}
            </template>
        </Column>
        <Column header="Estatus">
            <template #body="{data}">
                <div class="text-center w-full">
                    <Tag :value="data.stock ? 'Disponible' : 'Sin stock'" :severity="getSeverity(data)"></Tag>
                </div>
            </template>
        </Column>
        <Column header="">
            <template #header>
                <div class="flex justify-center w-full">
                    <Button v-if="can('create products')" icon="pi pi-plus" severity="success" rounded raised @click="openModalCreate"></Button>
                </div>
            </template>
            <template #body="{data}">
                <div class="flex gap-1 justify-center">
                    <Button raised icon="pi pi-barcode" v-tooltip.top="'Código de barras'" @click="openModalBarcode(data)"></Button>
                    <Button raised v-if="can('update products')" icon="pi pi-pencil" severity="warn" @click="openModalEdit(data)"></Button>
                    <Button raised v-if="can('delete products')" icon="pi pi-trash" severity="danger" @click="confirmDelete(route('api.products.destroy', { product: data.id }))"></Button>
                </div>
            </template>
        </Column>
    </DataTable>
</template>