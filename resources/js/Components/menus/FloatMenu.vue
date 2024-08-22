<script lang="ts" setup>
import { can } from '@/helpers';
import { useNavStore } from '@/stores/NavLinkStore';
import { Link } from '@inertiajs/vue3';
import Popover from 'primevue/popover';
import { computed, ref } from 'vue';

const navStore = useNavStore()
const op = ref();
const menuButton = ref(true)

const toggle = (event: Event) => {
  op.value.toggle(event);
}

const tabItems = computed(() => {
    return navStore.getLinks;
})
</script>
<template>
  <button
    class="fixed bottom-5 right-5 bg-blue-500 hover:bg-blue-600 text-white text-2xl text-center font-bold py-3 px-4 rounded-full shadow-lg z-50 xl:hidden"
    @click="toggle"
    aria-haspopup="true" aria-controls="overlay_panel">
    <i v-show="menuButton" class="pi pi-bars"></i>
    <i v-show="!menuButton" class="pi pi-times"></i>
  </button>

  <Popover ref="op" @hide="menuButton = true" @show="menuButton = false">
    <div class="flex flex-col gap-2 w-[13rem]">
      <template v-for="(item, index) in tabItems" :key="index">
        <Link v-if="can(item.permission)" :href="item.route" :class="{ 'bg-blue-200 pointer-events-none': item.active }" class="text-gray-600 flex items-center px-5 py-3">
          <i :class="item.icon" class="h-5 w-6"></i>
          <span>{{ item.label }}</span>
        </Link>
      </template>
    </div>
  </Popover>
</template>