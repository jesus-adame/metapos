<script lang="ts" setup>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import { onMounted, ref, watch } from 'vue';
import CustomerService from "@/Services/CustomerService";
import axios, { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';
import CreateCustomer from '../forms/CreateCustomer.vue';
import { can, formatDate } from '@/helpers';
import UserIcon from '../icons/UserIcon.vue';
import { useCustomerStore } from '@/stores/CustomerStore';
import EditCustomer from '../forms/EditCustomer.vue';
import { Customer } from '@/types';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

const customerService: CustomerService = new CustomerService()
const items = ref([])
const rows = ref(10)
const totalRecords = ref(0)
const page = ref(1)
const customerStore = useCustomerStore()
const modalCreate = ref(false)
const modalEdit = ref(false)
const selectedCustomer = ref<Customer | null>(null)
const confirm = useConfirm()
const toast = useToast()

const showModalCreate = () => {
    modalCreate.value = true
}

const hideModalCreate = () => {
    modalCreate.value = false
}

const showModalEdit = (customer: Customer) => {
    modalEdit.value = true
    selectedCustomer.value = customer
}

const hideModalEdit = () => {
    modalEdit.value = false
    fetchItems(page.value)
}

const fetchItems = (pageNumber: number) => {
    const result = customerService.paginate(pageNumber, rows.value)

    result.then((response: AxiosResponse) => {
        const customers = response.data;

        totalRecords.value = customers.total
        items.value = JSON.parse(JSON.stringify(customers.data))
        page.value = customers.current_page
    })
}

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchItems(pageNumber);
}

onMounted(() => {
    fetchItems(page.value)
})

watch(customerStore.getCustomers, () => {
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
    <Dialog v-model:visible="modalCreate" modal header="Nuevo cliente" :style="{ width: '35rem' }">
        <CreateCustomer @save="hideModalCreate"></CreateCustomer>
    </Dialog>

    <Dialog v-model:visible="modalEdit" modal header="Editar cliente" :style="{ width: '35rem' }">
        <EditCustomer :customer="selectedCustomer" @save="hideModalEdit"></EditCustomer>
    </Dialog>

    <DataTable :value="items" class="shadow-md" :paginator="true" :rows="rows" :lazy="true" :totalRecords="totalRecords" @page="onPage">
        <Column field="id" header="#"></Column>
        <Column field="name" header="Nombre">
            <template #body="{data}">
                <UserIcon>
                    {{ data.name }} {{ data.lastname }}
                </UserIcon>
            </template>
        </Column>
        <Column field="phone" header="Teléfono"></Column>
        <Column field="email" header="Email"></Column>
        <Column field="created_at" header="Creación">
            <template #body="{data}">
                {{ formatDate(data.created_at) }}
            </template>
        </Column>
        <Column field="updated_at" header="Edición">
            <template #body="{data}">
                {{ formatDate(data.updated_at) }}
            </template>
        </Column>
        <Column field="" header="">
            <template #header>
                <div class="w-full flex justify-center">
                    <Button icon="pi pi-plus" rounded severity="success" raised @click="showModalCreate"></Button>
                </div>
            </template>
            <template #body="{data}">
                <div class="w-full flex gap-1 justify-center">
                    <Button raised v-if="can('update customers')" @click="showModalEdit(data)" icon="pi pi-pencil" severity="warn"></Button>
                    <Button v-if="can('delete customers')" icon="pi pi-trash" severity="danger" @click="confirmDelete(route('api.customers.destroy', {customer: data.id}))"></Button>
                </div>
            </template>
        </Column>
    </DataTable>
</template>