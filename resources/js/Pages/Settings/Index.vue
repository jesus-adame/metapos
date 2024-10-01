<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Dialog from 'primevue/dialog';
import CreateSetting from '@/Components/forms/CreateSetting.vue';
import { onMounted, reactive, ref } from 'vue';
import ListUsers from '@/Components/tables/ListUsers.vue';
import ListCashRegisters from '@/Components/tables/ListCashRegisters.vue';
import ListLocations from '@/Components/tables/ListLocations.vue';
import ListRoles from '@/Components/tables/ListRoles.vue';
import SettingService from '@/Services/SettingService';
import axios, { AxiosResponse } from 'axios';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import { useToast } from 'primevue/usetoast';
import ListPrinters from '@/Components/tables/ListPrinters.vue';
import ListCurrencies from '@/Components/tables/ListCurrencies.vue';

const modalCreate = ref(false)
const activePage = ref('printers')
const settingService = new SettingService()
const toast = useToast()

const fetchItems = () => {
    settingService.fetchAll()
    .then((response: AxiosResponse) => {
        form.settings = response.data
    })
}

const form = reactive<{
    settings: any[],
}>({
    settings: [],
})

const setPage = (page: string) => {
    activePage.value = page
}

onMounted(() => {
    fetchItems()
})

const items = ref([
    {
        label: 'General',
        items: [
            // {
            //     label: 'Ajustes',
            //     icon: 'pi pi-cog',
            //     module: 'settings'
            // },
            {
                label: 'Impresoras',
                icon: 'pi pi-print',
                module: 'printers'
            },
            {
                label: 'Monedas',
                icon: 'pi pi-euro',
                module: 'currencies'
            }
        ]
    },
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
                module: 'locations',
                permissions: 'view locations'
            },
            {
                label: 'Cajas',
                icon: 'pi pi-shopping-cart',
                module: 'cashRegisters',
                permissions: 'view locations'
            }
        ]
    }
]);

const updateSettings = () => {
    axios.post(route('api.settings.store', form))
    .then((response: AxiosResponse) => {
        toast.add({ severity: 'success', summary: 'Correcto', detail: response.data.message, life: 2000 })
    })
}
</script>

<template>
    <Head title="Ajustes" />

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
                <div v-if="activePage == 'settings'" class="w-full shadow-md bg-gray-50 rounded-md p-4">
                    <form @submit.prevent="updateSettings">
                        <div v-for="(setting, index) in form.settings" :key="index" class="my-2">
                            <label :for="setting.label">{{ setting.label }}</label>
                            <InputText v-model="form.settings[index].value" class="ml-2"></InputText>
                        </div>
                        <Button label="Guardar cambios" type="submit"></Button>
                    </form>
                </div>
                <div v-if="activePage == 'roles'" class="w-full shadow-md">
                    <ListRoles></ListRoles>
                </div>
                <div v-if="activePage == 'users'" class="w-full shadow-md">
                    <ListUsers></ListUsers>
                </div>
                <div v-if="activePage == 'locations'" class="w-full shadow-md">
                    <ListLocations></ListLocations>
                </div>
                <div v-if="activePage == 'cashRegisters'" class="w-full shadow-md">
                    <ListCashRegisters></ListCashRegisters>
                </div>
                <div v-if="activePage == 'printers'" class="w-full shadow-md">
                    <ListPrinters></ListPrinters>
                </div>
                <div v-if="activePage == 'currencies'" class="w-full shadow-md">
                    <ListCurrencies></ListCurrencies>
                </div>
            </div>
        </div>
    </UserLayout>
    <Dialog v-model:visible="modalCreate" modal header="Registrar ajuste" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <CreateSetting class="mt-4"></CreateSetting>
    </Dialog>
</template>
