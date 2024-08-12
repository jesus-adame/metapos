<script lang="ts" setup>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import { onMounted, ref } from 'vue';
import CustomerService from "@/Services/CustomerService";
import { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';
import CreateCustomer from '../forms/CreateCustomer.vue';
import { formatDate } from '@/helpers';
import UserIcon from '../icons/UserIcon.vue';

const customerService: CustomerService = new CustomerService()
const items = ref([])
const rows = ref(10)
const totalRecords = ref(0)
const page = ref(1)

const modalCreate = ref(false)

const showModalCreate = () => {
    modalCreate.value = true
}

const hideModalCreate = () => {
    modalCreate.value = false
    fetchUsers(page.value)
}

const fetchUsers = (pageNumber: number) => {
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
    fetchUsers(pageNumber);
}

onMounted(() => {
    fetchUsers(page.value)
})
</script>
<template>
    <Dialog v-model:visible="modalCreate" modal header="Registrar cliente" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <CreateCustomer class="mt-4" @save="hideModalCreate"></CreateCustomer>
    </Dialog>

    <DataTable :value="items" class="shadow-md" :paginator="true" :rows="rows" :lazy="true" :totalRecords="totalRecords" @page="onPage">
        <Column field="id" header="#"></Column>
        <Column field="first_name" header="Nombre">
            <template #body="slot">
                <UserIcon>
                    {{ slot.data.first_name }} {{ slot.data.last_name }}
                </UserIcon>
            </template>
        </Column>
        <Column field="phone" header="Teléfono"></Column>
        <Column field="email" header="Email"></Column>
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
                <div class="w-full flex justify-center">
                    <Button icon="pi pi-plus" rounded severity="success" raised @click="showModalCreate"></Button>
                </div>
            </template>
            <template #body>
                <div class="w-full flex justify-center">
                    <Button icon="pi pi-trash" severity="danger"></Button>
                </div>
            </template>
        </Column>
    </DataTable>
</template>