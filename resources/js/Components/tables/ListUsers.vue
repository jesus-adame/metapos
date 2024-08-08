<script lang="ts" setup>
import moment from 'moment';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import CreateUser from '@/Components/forms/CreateUser.vue';
import { onMounted, ref } from 'vue';
import UserService from "@/Services/UserService";
import { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';

const userService: UserService = new UserService()
const items = ref([])
const rows = ref(10)
const totalRecords = ref(0)
const page = ref(1)

const modalCreateUser = ref(false)

const showModalCreate = () => {
    modalCreateUser.value = true
}

const hideModalCreate = () => {
    modalCreateUser.value = false
    fetchUsers(page.value)
}

const fetchUsers = (pageNumber: number) => {
    const result = userService.paginate(pageNumber, rows.value)

    result.then((response: AxiosResponse) => {
        const users = response.data;

        totalRecords.value = users.total
        items.value = JSON.parse(JSON.stringify(users.data))
        page.value = users.current_page
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
    <DataTable :value="items" class="shadow-md" :paginator="true" :rows="rows" :lazy="true" :totalRecords="totalRecords" @page="onPage">
        <Column field="id" header="#"></Column>
        <Column field="name" header="Nombre"></Column>
        <Column field="email" header="Email"></Column>
        <Column field="created_at" header="Creación">
            <template #body="slot">
                {{ moment(slot.data.created_at).calendar() }}
            </template>
        </Column>
        <Column field="updated_at" header="Edición">
            <template #body="slot">
                {{ moment(slot.data.updated_at).calendar() }}
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

    <Dialog v-model:visible="modalCreateUser" modal header="Registrar usuario" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <CreateUser class="mt-4" @save="hideModalCreate"></CreateUser>
    </Dialog>
</template>