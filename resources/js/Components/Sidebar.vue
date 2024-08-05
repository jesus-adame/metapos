<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import VirtualScroller from 'primevue/virtualscroller';
import { ref } from 'vue';

const tabItems = [
  {
    icon: 'fi fi-sr-apps h-5 w-5',
    label: 'Panel',
    pageUrl: route('dashboard'),
    active: route().current('dashboard')
  },
  {
    icon: 'fi fi-sr-shopping-cart h-5 w-5',
    label: 'POS',
    pageUrl: route('sales.create'),
    active: route().current('sales.create')
  },
  {
    icon: 'fi fi-sr-money-bill-wave h-5 w-5',
    label: 'Caja',
    pageUrl: route('cash-flows.index'),
    active: route().current('cash-flows.index')
  },
  {
    icon: 'fi fi-sr-chart-histogram h-5 w-5',
    label: 'Ventas',
    pageUrl: route('sales.index'),
    active: route().current('sales.index')
  },
  {
    icon: 'fi fi-sr-stats h-5 w-5',
    label: 'Compras',
    pageUrl: route('purchases.index'),
    active: route().current('purchases.index')
  },
  // {
  //   icon: 'fi fi-sr-bank h-5 w-5',
  //   label: 'Banco',
  //   pageUrl: route('bank-transactions.index'),
  //   active: route().current('bank-transactions.index')
  // },
  {
    icon: 'fi fi-sr-boxes h-5 w-5',
    label: 'Inventario',
    pageUrl: route('products.index'),
    active: route().current('products.index')
  },
  {
    icon: 'fi fi-sr-users-alt h-5 w-5',
    label: 'Usuarios',
    pageUrl: route('users.index'),
    active: route().current('users.index')
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
<div
class="-translate-x-80 sidebar fixed inset-0 z-50 h-screen w-[3.35rem] hover:w-56 transition-transform duration-300 xl:translate-x-0"
@mouseenter="handleMouseEnter"
@mouseleave="handleMouseLeave">
  <div class="min-h-screen w-full overflow-hidden border-r hover:shadow-lg bg-gradient-to-br from-gray-900 to-gray-700">
    <div class="flex h-screen flex-col justify-between pt-2 pb-6">
      <div>
        <div class="w-max p-2.5">
          <img src="https://tailus.io/images/logo.svg" class="w-32" alt="">
        </div>
        <div class="mt-6 space-y-2 tracking-wide text-gray-100 reveal-menu">
          <VirtualScroller :items="tabItems" :itemSize="10" style="height: 65vh; overflow-x: hidden;" :class="{ 'overflow-hidden': !isHovered }" orientation="vertical">
            <template v-slot:item="{ item, options }">
              <div class="min-w-max">
                <Link :href="item.pageUrl" :class="{ 'bg-gradient-to-r from-sky-600 to-cyan-400 pointer-events-none': item.active }" class="group flex items-center space-x-4 px-5 py-3">
                  <i :class="item.icon"></i>
                  <span>{{ item.label }}</span>
                </Link>
              </div>
            </template>
          </VirtualScroller>
        </div>
      </div>
      <div class="w-max -mb-3">
        <Link :href="route('settings.index')" class="group flex items-center space-x-4 px-4 py-3 text-gray-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:fill-cyan-600" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
          </svg>
          <span class="group-hover:text-gray-300">Ajustes</span>
        </Link>
      </div>
    </div>
  </div>
</div>
</template>

<style scoped>
.sidebar {
  transition:all .4s ease-in-out;
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