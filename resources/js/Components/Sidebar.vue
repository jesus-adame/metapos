<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import VirtualScroller from 'primevue/virtualscroller';
import { ref } from 'vue';
import { TabItem } from '@/types';
import { can } from '@/helpers';
import FloatMenu from './menus/FloatMenu.vue';
import { PERMISSIONS } from '@/consts/role_permissions';

const tabItems: TabItem[] = [
  {
    icon: 'fi fi-sr-apps',
    label: 'Panel',
    route: route('home'),
    active: route().current('home'),
    permission: PERMISSIONS.VIEW_FINANCES
  },
  {
    icon: 'fi fi-sr-shopping-cart',
    label: 'POS',
    route: route('sales.create'),
    active: route().current('sales.create'),
    permission: PERMISSIONS.CREATE_SALES
  },
  {
    icon: 'fi fi-sr-drawer-empty',
    label: 'Caja',
    route: route('cash-flows.index'),
    active: route().current('cash-flows.index'),
    permission: PERMISSIONS.VIEW_SALES
  },
  {
    icon: 'fi fi-ss-receipt',
    label: 'Ventas',
    route: route('sales.index'),
    active: route().current('sales.index'),
    permission: PERMISSIONS.VIEW_SALES
  },
  {
    icon: 'fi fi-sr-bags-shopping',
    label: 'Compras',
    route: route('purchases.index'),
    active: route().current('purchases.index'),
    permission: PERMISSIONS.VIEW_PURCHASES
  },
  {
    icon: 'fi fi-ss-expense',
    label: 'Gastos',
    route: route('expenses.index'),
    active: route().current('expenses.index'),
    permission: PERMISSIONS.VIEW_EXPENSES
  },
  {
    icon: 'fi fi-sr-boxes',
    label: 'Inventario',
    route: route('products.index'),
    active: route().current('products.index'),
    permission: PERMISSIONS.VIEW_PRODUCTS
  },
  {
    icon: 'fi fi-sr-user',
    label: 'Cientes',
    route: route('customers.index'),
    active: route().current('customers.index'),
    permission: PERMISSIONS.VIEW_CUSTOMERS
  },
  {
    icon: 'fi fi-sr-tags',
    label: 'Categorías',
    route: route('categories.index'),
    active: route().current('categories.index'),
    permission: PERMISSIONS.VIEW_CATEGORIES
  },
  {
    icon: 'fi fi-sr-ballot-check',
    label: 'Reportes',
    route: route('reports.index'),
    active: route().current('reports.index'),
    permission: PERMISSIONS.VIEW_FINANCES
  },
];

const isHovered = ref(false)

function handleMouseEnter() {
  isHovered.value = true;
}

function handleMouseLeave() {
  isHovered.value = false;
}
</script>

<template>
  <FloatMenu></FloatMenu>

  <div
  class="-translate-x-80 sidebar fixed inset-0 z-50 h-screen w-[3.7rem] hover:w-56 transition-transform duration-300 xl:translate-x-0"
  @mouseenter="handleMouseEnter"
  @mouseleave="handleMouseLeave">
    <div class="min-h-screen w-full overflow-hidden border-r hover:shadow-lg bg-blue-950 shadow-md">
      <div class="flex h-screen flex-col justify-between pt-2 pb-6">
        <div>
          <div class="w-max p-2.5">
            <div class="flex items-center justify-center">
              <div class="w-9">
                <img src="/logos/logo-6-only-white.png" alt="Logo" class="w-full">
              </div>
              <span class="ml-3 font-black text-lg text-gray-200">METAPOS</span>
            </div>
          </div>
          <div class="mt-4 space-y-2 tracking-wide text-gray-100 reveal-menu">
            <VirtualScroller :items="tabItems" :itemSize="10" style="height: 65vh; overflow-x: hidden;" :class="{ 'overflow-hidden': !isHovered }" orientation="vertical">
              <template v-slot:item="{ item }">
                <div class="min-w-max">
                  <Link v-if="can(item.permission)" :href="item.route" :class="{ 'bg-blue-800 pointer-events-none': item.active }" class="group flex items-center space-x-4 px-5 py-3">
                    <i :class="item.icon" class="h-5 w-6"></i>
                    <span>{{ item.label }}</span>
                  </Link>
                </div>
              </template>
            </VirtualScroller>
          </div>
        </div>
        <div v-if="can('view settings')">
          <Link :href="route('settings.index')" class="group flex gap-2 items-center space-x-4 px-5 py-3 text-gray-100">
            <i class="pi pi-cog"></i>
            <span class="group-hover:text-gray-300">Ajustes</span>
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.sidebar {
  transition:all .2s ease-in-out;
}

.reveal-menu {
  position: relative;
  overflow: hidden;
}

.reveal-menu .group {
  transition: transform 0.3s ease, background-color 0.3s ease;
}

.reveal-menu .group:hover {
  background-color: rgba(80, 133, 173, 0.2);
}

.reveal-menu .group .group-hover\\:text-gray-300 {
  transition: color 0.3s ease;
}

.reveal-menu .group:hover .group-hover\\:text-gray-300 {
  color: gray;
}

</style>