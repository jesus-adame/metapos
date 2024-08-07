<template>
    <div>
        <!-- <Button icon="pi pi-bars" @click="toggleSidebar" /> -->
        <Drawer v-model:visible="sidebarVisible" header="Meta POS" @hide="hideSidebar">
            <Menu :model="items" class="mb-2" :pt="{ root: 'border-0' }">
                <template #item="{ item }">
                    <Link v-if="item.route" :href="item.route" :class="{ 'bg-gradient-to-r from-gray-300 to-gray-200 pointer-events-none': item.active }" class="relative flex items-center py-2 px-3 text-surface-700 dark:text-white/80 no-underline overflow-hidden cursor-pointer select-none">
                        <span :class="item.icon" />
                        <span class="ml-4">{{ item.label }}</span>
                    </Link>
                </template>
            </Menu>
            <PanelMenu :model="panelMenuItems" :pt="panelMenuConfig">
                <!-- templates -->
            </PanelMenu>
        </Drawer>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Drawer from 'primevue/drawer';
import Menu from 'primevue/menu';
import PanelMenu from 'primevue/panelmenu';
import { Link } from '@inertiajs/vue3';

const panelMenuConfig = {
    panel: 'border-0 p-0 overflow-hidden mb-2 rounded-md',
    headerLink: 'relative leading-none flex items-center py-3 px-3 select-none cursor-pointer no-underline',
    itemLink: 'relative leading-none flex items-center py-3 px-3 rounded-[4px] text-surface-700 dark:text-white/80 hover:bg-surface-100 dark:hover:bg-[rgba(255,255,255,0.03)] hover:text-surface-700 dark:hover:text-white/80 cursor-pointer no-underline select-none overflow-hidden'
}

const emit = defineEmits(['hideSidebar'])
const sidebarVisible = ref(false);

const hideSidebar = () => {
    alert('Entra')
    emit('hideSidebar')
}

// Elementos del menú
const items = ref([
    {
        icon: 'pi pi-th-large',
        label: 'Panel',
        route: route('dashboard'),
        active: route().current('dashboard')
    },
    {
        icon: 'pi pi-shopping-cart',
        label: 'POS',
        route: route('sales.create'),
        active: route().current('sales.create')
    },
    {
        icon: 'pi pi-money-bill',
        label: 'Caja',
        route: route('cash-flows.index'),
        active: route().current('cash-flows.index')
    },
    {
        icon: 'pi pi-shopping-bag',
        label: 'Ventas',
        route: route('sales.index'),
        active: route().current('sales.index')
    },
    {
        icon: 'pi pi-wallet',
        label: 'Compras',
        route: route('purchases.index'),
        active: route().current('purchases.index')
    },
    {
        icon: 'pi pi-box',
        label: 'Inventario',
        route: route('products.index'),
        active: route().current('products.index')
    },
    {
        icon: 'pi pi-user-plus',
        label: 'Usuarios',
        route: route('users.index'),
        active: route().current('users.index')
    },
    {
        label: 'Settings',
        icon: 'pi pi-fw pi-cog',
        route: route('settings.index')
    },
]);

const panelMenuItems = ref([
    {
        label: 'Options',
        icon: 'pi pi-fw pi-wrench',
        items: [
            {
                label: 'Save',
                icon: 'pi pi-fw pi-save',
            },
            {
                label: 'Update',
                icon: 'pi pi-fw pi-refresh',
            }
        ]
    },
    {
        label: 'Account',
        icon: 'pi pi-fw pi-user',
        items: [
            {
                label: 'Profile',
                icon: 'pi pi-fw pi-user-edit',
            },
            {
                label: 'Logout',
                icon: 'pi pi-fw pi-sign-out',
            }
        ]
    }
]);

// Función para alternar la visibilidad del sidebar
// const toggleSidebar = () => {
//     sidebarVisible.value = !sidebarVisible.value;
// };
</script>
