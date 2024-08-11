<script lang="ts" setup>
import { formatMoneyNumber } from '@/helpers';
import { Product } from '@/types';
import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable, { DataTablePageEvent } from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Image from 'primevue/image';
import Tag from 'primevue/tag';
import { onMounted, ref } from 'vue';
import CreateProduct from '../forms/CreateProduct.vue';
import ProductService from '@/Services/ProductService';
import axios, { AxiosResponse } from 'axios';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import EditProduct from '../forms/EditProduct.vue';
import { Link } from '@inertiajs/vue3';

const createModal = ref<Boolean>(false)
const editModal = ref<Boolean>(false)
const items = ref<Product[]>([])
const rows = ref<number>(10)
const productService = new ProductService()
const toast = useToast()
const confirm = useConfirm()
const product = ref<Product | null>(null)
const page = ref(1)
const totalRecords = ref(0)

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
    fetchItems(page.value)
}

const openModalEdit = (productData: Product) => {
    product.value = productData
    editModal.value = true
}

const closeModalEdit = () => {
    editModal.value = false
    fetchItems(page.value)
}

const fetchItems = (pageNumber: number) => {
    productService.paginate(pageNumber)
    .then((response: AxiosResponse) => {
        const paginate = response.data

        totalRecords.value = paginate.total
        items.value = paginate.data
    })
}

onMounted(() => {
    fetchItems(page.value)
})

const deleteItem = (url: string) => {
    axios.post(url, { _method: 'delete' })
    .then((response: AxiosResponse) => {
        console.log(response);
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 1500 })
        fetchItems(page.value)
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
</script>
<template>
    <Dialog v-model:visible="createModal" header="Nuevo producto" :modal="true">
        <CreateProduct class="mt-4" @save="closeModalCreate"></CreateProduct>
    </Dialog>

    <Dialog v-model:visible="editModal" header="Editar producto" :modal="true">
        <EditProduct class="mt-4" @save="closeModalEdit" :product="product ?? undefined"></EditProduct>
    </Dialog>

    <ConfirmDialog></ConfirmDialog>

    <DataTable :value="items" paginator :rows="rows" @page="onPage" :totalRecords="totalRecords">
        <Column field="id" header="#"></Column>
        <Column field="name" header="Producto">
            <template #body="slot">
                <div class="flex items-center">
                    <Image
                        :src="`${slot.data.image_url}`"
                        :alt="slot.data.image"
                        v-if="slot.data.image"
                        class="w-24 text-white shadow-md mr-8 rounded-md overflow-hidden"
                        preview
                    />
                    <div>
                        <Link :href="route('products.edit', {product: slot.data.id})">
                            <p class="text-lg font-semibold">{{ slot.data.name }}</p>
                        </Link>
                        <span>Code: {{ slot.data.code }}</span>
                    </div>
                </div>
            </template>
        </Column>
        <Column field="description" header="Descripción"></Column>
        <Column field="cost" header="">
            <template #header>
                <div class="w-full text-center">
                    Precio de compra
                </div>
            </template>
            <template #body="slot">
                <div class="w-full text-center">
                    {{ formatMoneyNumber(slot.data.cost) }}
                </div>
            </template>
        </Column>
        <Column field="price" header="">
            <template #header>
                <div class="w-full text-center">
                    Precio de venta
                </div>
            </template>
            <template #body="slot">
                <div class="w-full text-center">
                    {{ formatMoneyNumber(slot.data.price) }}
                </div>
            </template>
        </Column>
        <Column header="">
            <template #header>
                <div class="w-full text-center">
                    Inventario
                </div>
            </template>
            <template #body="slot">
                <div class="text-center w-full">
                    <Tag :value="slot.data.stock || 0" :severity="getSeverity(slot.data)"></Tag>
                </div>
            </template>
        </Column>
        <Column field="unit" header="Unidad">
            <template #body="slot">
                {{ slot.data.unit_type }}
            </template>
        </Column>
        <Column header="">
            <template #header>
                <div class="flex justify-center w-full">
                    <Button icon="pi pi-plus" severity="success" rounded raised @click="openModalCreate"></Button>
                </div>
            </template>
            <template #body="slot">
                <div class="flex justify-center">
                    <Button icon="pi pi-pencil" class="mr-1" @click="openModalEdit(slot.data)"></Button>
                    <Button icon="pi pi-trash" severity="danger" @click="confirmDelete(route('api.products.destroy', { product: slot.data.id }))"></Button>
                </div>
            </template>
        </Column>
    </DataTable>
</template>