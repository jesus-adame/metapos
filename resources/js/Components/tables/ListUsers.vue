<script lang="ts" setup>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import CreateUser from '@/Components/forms/CreateUser.vue';
import { onMounted, ref } from 'vue';
import UserService from "@/Services/UserService";
import axios, { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';
import UserIcon from '../icons/UserIcon.vue';
import EditUser from '../forms/EditUser.vue';
import { User } from '@/types';
import { can, formatDate } from '@/helpers';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

const userService: UserService = new UserService()
const items = ref([])
const rows = ref(10)
const totalRecords = ref(0)
const page = ref(1)
const user = ref<User | null>(null)
const toast = useToast()
const confirm = useConfirm()

const modalCreateUser = ref(false)
const modalEditUser = ref(false)

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

const openModalEdit = (selectedUser: User) => {
    user.value = {...selectedUser}
    modalEditUser.value = true
}

const hideModalEdit = () => {
    modalEditUser.value = false
    fetchUsers(page.value)
}

onMounted(() => {
    fetchUsers(page.value)
})

const deleteItem = (url: string) => {
    axios.post(url, { _method: 'delete' })
    .then((response: AxiosResponse) => {
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 1500 })
        fetchUsers(page.value)
    })
    .catch(reject => {
        console.error(reject.response.data.errors);
        toast.add({ summary: 'Error', detail: reject.response.data.message, severity: 'error', life: 3000 })
    })
}

const confirmDelete = (url: string) => {
    confirm.require({
        header: '¿Está seguro?',
        message: 'Se eliminará el usuario y todos sus datos',
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
    <Dialog v-model:visible="modalCreateUser" modal header="Registrar usuario" :style="{ width: '35rem' }">
        <CreateUser class="mt-4" @save="hideModalCreate"></CreateUser>
    </Dialog>

    <Dialog v-model:visible="modalEditUser" modal header="Editar usuario" :style="{ width: '35rem' }">
        <EditUser class="mt-4" :user="user" @save="hideModalEdit"></EditUser>
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
                    <Button v-if="can('create users')" icon="pi pi-plus" rounded severity="success" raised @click="showModalCreate"></Button>
                </div>
            </template>
            <template #body="{data}">
                <div class="flex gap-1 justify-center">
                    <Button raised v-if="can('update users')" icon="pi pi-pencil" severity="warn" @click="openModalEdit(data)"></Button>
                    <Button raised v-if="can('delete users')" icon="pi pi-trash" severity="danger" @click="confirmDelete(route('api.users.destroy', {user: data.id}))"></Button>
                </div>
            </template>
        </Column>
    </DataTable>
</template>