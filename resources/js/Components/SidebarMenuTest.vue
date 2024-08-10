<template>
    <Drawer v-model:visible="sidebarVisible" header="Meta POS">
        <template #header>
            <div class="font-semibold text-xl my-1">
                <div class="flex items-center">
                    <ApplicationLogo class="w-12 fill-current text-gray-600"></ApplicationLogo>
                    <span class="ml-3">METAPOS</span>
                </div>
            </div>
        </template>
        <Menu :model="menuItems" class="mb-2" :pt="{ root: 'border-0' }">
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

    <div @mouseenter="openSidebar" class="sidebar w-10 overflow-hidden flex flex-col relative w-[5rem] border-0 dark:border shadow-lg bg-surface-0 text-surface-700 transition-transform duration-300 pointer-events-auto">
        <div class="flex items-center justify-center shrink-0 p-[1.125rem] bg-surface-0 text-surface-700 mt-[2px]">
            <div class="font-semibold text-xl my-1">
                <ApplicationLogo class="w-12 fill-current text-gray-600"></ApplicationLogo>
            </div>
        </div>
        <div class="p-[1.125rem] pt-0 h-full w-full grow overflow-y-auto text-center items-center">
            <div class="mb-2 border-0">
                <ul class="m-0 p-0">
                    <li v-for="(item, index) in menuItems" :key="index" class="my-[2px]">
                        <span :class="{ 'bg-gradient-to-r from-gray-300 to-gray-200': item.active }" class="py-3 px-3 relative flex rounded">
                            <span :class="item.icon" />
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Menu from 'primevue/menu';
import PanelMenu from 'primevue/panelmenu';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from './ApplicationLogo.vue';

const panelMenuConfig = {
    panel: 'border-0 p-0 overflow-hidden mb-2 rounded-md',
    headerLink: 'relative leading-none flex items-center py-3 px-3 select-none cursor-pointer no-underline',
    itemLink: 'relative leading-none flex items-center py-3 px-3 rounded-[4px] text-surface-700 dark:text-white/80 hover:bg-surface-100 dark:hover:bg-[rgba(255,255,255,0.03)] hover:text-surface-700 dark:hover:text-white/80 cursor-pointer no-underline select-none overflow-hidden'
}

const emit = defineEmits(['openSidebar'])

const sidebarVisible = ref(false);

const openSidebar = () => {
    sidebarVisible.value = true
}

// Elementos del menú
const menuItems = ref([
    {
        icon: 'pi pi-th-large',
        label: 'Panel',
        route: route('home'),
        active: route().current('home')
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
</script>

<style scoped>
.sidebar {
    height: 100vh;
}

.custom-menu .p-menu-list,
.custom-panel-menu .p-panelmenu-panel {
    border: none; /* Elimina cualquier borde */
}

.custom-menu .p-menuitem-link,
.custom-panel-menu .p-panelmenu-header-link {
    padding: 10px;
}

.custom-panel-menu .p-panelmenu-content {
    padding: 0;
}
</style>
