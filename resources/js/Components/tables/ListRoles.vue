<script lang="ts" setup>
import moment from 'moment';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import { onMounted, ref } from 'vue';
import RoleService from "@/Services/RoleService";
import axios, { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';
import UserIcon from '../icons/UserIcon.vue';
import { Role } from '@/types';
import CreateRole from '../forms/CreateRole.vue';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

const roleService: RoleService = new RoleService()
const items = ref<Role[]>([])
const rows = ref(10)
const totalRecords = ref(0)
const page = ref(1)
const confirm = useConfirm()
const toast = useToast()

const modalCreateRole = ref(false)

const showModalCreate = () => {
    modalCreateRole.value = true
}

const hideModalCreate = () => {
    modalCreateRole.value = false
    fetchRoles(page.value)
}

const fetchRoles = (pageNumber: number) => {
    const result = roleService.paginate(pageNumber, rows.value)

    result.then((response: AxiosResponse) => {
        const roles = response.data;

        totalRecords.value = roles.total
        items.value = JSON.parse(JSON.stringify(roles.data))
        page.value = roles.current_page
    })
}

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchRoles(pageNumber);
}

onMounted(() => {
    fetchRoles(page.value)
})

const deleteItem = (url: string) => {
    axios.post(url, { _method: 'delete' })
    .then((response: AxiosResponse) => {
        console.log(response);
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 1500 })
        fetchRoles(page.value)
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
</script>
<template>
    <Dialog v-model:visible="modalCreateRole" modal header="Nuevo Rol" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <CreateRole @save="hideModalCreate"></CreateRole>
    </Dialog>

    <DataTable :value="items" class="shadow-md" :paginator="true" :rows="rows" :lazy="true" :totalRecords="totalRecords" @page="onPage">
        <Column field="id" header="#"></Column>
        <Column field="name" header="Nombre">
            <template #body="{ data }">
                <UserIcon>
                    {{ data.name }}
                </UserIcon>
            </template>
        </Column>
        <Column field="email" header="Email"></Column>
        <Column field="created_at" header="Creación">
            <template #body="{ data }">
                {{ moment(data.created_at).calendar() }}
            </template>
        </Column>
        <Column field="updated_at" header="Edición">
            <template #body="{ data }">
                {{ moment(data.updated_at).calendar() }}
            </template>
        </Column>
        <Column field="" header="">
            <template #header>
                <div class="w-full flex justify-center">
                    <Button icon="pi pi-plus" rounded severity="success" raised @click="showModalCreate"></Button>
                </div>
            </template>
            <template #body="{ data }">
                <div class="w-full flex justify-center">
                    <Button icon="pi pi-trash" severity="danger" @click="confirmDelete(route('api.roles.destroy', {role: data.id }))"></Button>
                </div>
            </template>
        </Column>
    </DataTable>
</template>