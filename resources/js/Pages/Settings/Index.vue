<script setup>
import { usePage, Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import CreateSetting from '@/Components/forms/CreateSetting.vue';
import { ref } from 'vue';
import ListUsers from '@/Components/tables/ListUsers.vue';
import ListCashRegisters from '@/Components/tables/ListCashRegisters.vue';
import ListBranches from '@/Components/tables/ListBranches.vue';
import ListRoles from '@/Components/tables/ListRoles.vue';

const { props } = usePage();
const settings = props.settings;
const modalCreate = ref(false)
const activePage = ref('users')

const showModalCreate = () => {
    modalCreate.value = true
}

const hideModalCreate = () => {
    modalCreate.value = false
}

const setPage = (page) => {
    activePage.value = page
}

const items = ref([
    {
        label: 'Usuarios',
        items: [
            {
                label: 'Usuarios',
                icon: 'pi pi-user',
                module: 'users'
            },
            {
                label: 'Roles y permisos',
                icon: 'pi pi-lock',
                module: 'roles'
            }
        ]
    },
    {
        label: 'Sucursales',
        items: [
            {
                label: 'Sucursales',
                icon: 'pi pi-building',
                module: 'branches'
            },
            // {
            //     label: 'Almacenes',
            //     icon: 'pi pi-building',
            //     module: 'warehouses'
            // },
            {
                label: 'Cajas',
                icon: 'pi pi-shopping-cart',
                module: 'cashRegisters'
            }
        ]
    },
    // {
    //     label: 'Ajustes',
    //     items: [
    //         {
    //             label: 'Settings',
    //             icon: 'pi pi-cog',
    //             module: 'settings'
    //         }
    //     ]
    // }
]);
</script>

<template>
    <Head title="Settings" />

    <UserLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajustes</h2>
        </template>

        <div class="flex justify-between mt-6 w-full">
            <div class="w-1/4">
                <Menu :model="items" class="w-full rounded-0 shadow-md">
                    <template #item="{ item }">
                        <button class="p-2 w-full text-left rounded" :class="{ 'bg-gray-300': activePage == item.module }" @click="setPage(item.module)">
                            <span :class="item.icon" />
                            <span class="ml-2">{{ item.label }}</span>
                        </button>
                    </template>
                </Menu>
            </div>
            <div class="w-3/4 ml-2">
                <div v-if="activePage == 'roles'">
                    <ListRoles></ListRoles>
                </div>
                <div v-if="activePage == 'users'" class="w-full shadow-md">
                    <ListUsers></ListUsers>
                </div>
                <div v-if="activePage == 'settings'" class="w-full shadow-md">
                    <DataTable :value="settings" paginator :rows="8">
                        <Column field="id" header="#"></Column>
                        <Column field="key" header="Clave"></Column>
                        <Column field="value" header="Valor"></Column>
                        <Column field="" header="">
                            <template #header>
                                <Button icon="pi pi-plus" rounded severity="success" raised @click="showModalCreate"></Button>
                            </template>
                        </Column>
                    </DataTable>
                </div>
                <div v-if="activePage == 'branches'" class="w-full shadow-md">
                    <ListBranches></ListBranches>
                </div>
                <div v-if="activePage == 'cashRegisters'" class="w-full shadow-md">
                    <ListCashRegisters></ListCashRegisters>
                </div>
            </div>
        </div>

    </UserLayout>
    <Dialog v-model:visible="modalCreate" modal header="Registrar ajuste" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <CreateSetting class="mt-4"></CreateSetting>
    </Dialog>
</template>
