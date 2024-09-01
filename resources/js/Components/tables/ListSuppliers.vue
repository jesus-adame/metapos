<script lang="ts" setup>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import { onMounted, ref } from 'vue';
import SupplierService from "@/Services/SupplierService";
import axios, { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';
import CreateSupplier from '../forms/CreateSupplier.vue';
import { can, formatDate } from '@/helpers';
import UserIcon from '../icons/UserIcon.vue';
import EditSupplier from '../forms/EditSupplier.vue';
import { Supplier } from '@/types';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

const supplierService: SupplierService = new SupplierService()
const items = ref([])
const rows = ref(10)
const totalRecords = ref(0)
const page = ref(1)
const modalCreate = ref(false)
const modalEdit = ref(false)
const selectedSupplier = ref<Supplier | null>(null)
const confirm = useConfirm()
const toast = useToast()

const showModalCreate = () => {
    modalCreate.value = true
}

const hideModalCreate = () => {
    modalCreate.value = false
    fetchItems(page.value)
}

const showModalEdit = (supplier: Supplier) => {
    modalEdit.value = true
    selectedSupplier.value = supplier
}

const hideModalEdit = () => {
    modalEdit.value = false
    fetchItems(page.value)
}

const fetchItems = (pageNumber: number) => {
    const result = supplierService.paginate(pageNumber, rows.value)

    result.then((response: AxiosResponse) => {
        const suppliers = response.data;

        totalRecords.value = suppliers.total
        items.value = JSON.parse(JSON.stringify(suppliers.data))
        page.value = suppliers.current_page
    })
}

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchItems(pageNumber);
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
        message: 'Se eliminará el cliente',
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
</script>
<template>
    <Dialog v-model:visible="modalCreate" modal header="Nuevo proveedor" :style="{ width: '35rem' }">
        <CreateSupplier class="mt-4" @save="hideModalCreate"></CreateSupplier>
    </Dialog>
    <Dialog v-model:visible="modalEdit" modal header="Editar proveedor" :style="{ width: '35rem' }">
        <EditSupplier class="mt-4" @save="hideModalEdit" :supplier="selectedSupplier"></EditSupplier>
    </Dialog>

    <DataTable :value="items" class="shadow-md" :paginator="true" :rows="rows" :lazy="true" :totalRecords="totalRecords" @page="onPage">
        <Column field="id" header="#"></Column>
        <Column field="name" header="Nombre">
            <template #body="slot">
                <UserIcon>
                    {{ slot.data.name }} {{ slot.data.lastname }}
                </UserIcon>
            </template>
        </Column>
        <Column field="email" header="Email"></Column>
        <Column field="company_name" header="Empresa"></Column>
        <Column field="phone" header="Teléfono"></Column>
        <Column field="created_at" header="Creación">
            <template #body="slot">
                {{ formatDate(slot.data.created_at) }}
            </template>
        </Column>
        <Column field="updated_at" header="Edición">
            <template #body="slot">
                {{ formatDate(slot.data.updated_at) }}
            </template>
        </Column>
        <Column field="" header="">
            <template #header>
                <div v-if="can('create suppliers')" class="w-full flex justify-center">
                    <Button icon="pi pi-plus" rounded severity="success" raised @click="showModalCreate"></Button>
                </div>
            </template>
            <template #body="{data}">
                <div  class="flex gap-1 justify-center">
                    <Button raised v-if="can('update suppliers')" @click="showModalEdit(data)" icon="pi pi-pencil" severity="warn"></Button>
                    <Button raised v-if="can('delete suppliers')" @click="confirmDelete(route('api.suppliers.destroy', {supplier: data.id}))" icon="pi pi-trash" severity="danger"></Button>
                </div>
            </template>
        </Column>
    </DataTable>
</template>