<script lang="ts" setup>
import LoggedUserDropdown from '@/Components/dropdowns/LoggedUserDropdown.vue';
import { Link } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { ref } from 'vue';

const isFullScreen = ref(false)

function toggleFullScreen() {
  if (!document.fullscreenElement) {
    isFullScreen.value = true
    document.documentElement.requestFullscreen();
  } else if (document.exitFullscreen) {
    isFullScreen.value = false
    document.exitFullscreen();
  }
}
</script>
<template>
    <nav class="block w-full max-w-full bg-transparent text-white shadow-none rounded-xl transition-all px-0 py-1">
      <div class="flex flex-col-reverse justify-between gap-6 md:flex-row md:items-center">
        <div class="capitalize">
          <slot/>
          <nav aria-label="breadcrumb" class="w-max mt-2">
            <ol class="flex flex-wrap items-center w-full bg-opacity-60 rounded-md bg-transparent p-0 transition-all">
              <li class="flex items-center text-gray-gray-900 antialiased font-sans text-sm font-normal leading-normal cursor-pointer transition-colors duration-300 hover:text-light-gray-500">
                <Link :href="route('home')" class="block antialiased font-sans text-sm leading-normal text-gray-900 font-normal opacity-50 transition-all hover:text-gray-500 hover:opacity-100">
                  Panel
                </Link>
              </li>
              <li class="flex items-center text-gray-900 antialiased font-sans text-sm font-normal leading-normal pointer-events-none">
                <span class="text-gray-500 text-sm font-normal leading-normal mx-2 select-none">/</span>
                <p class="block antialiased text-sm leading-normal text-gray-900 font-normal">home</p>
              </li>
            </ol>
          </nav>
        </div>
        <div class="flex items-center">
          <Button icon="pi pi-bell" class="mr-3" rounded severity="secondary"></Button>
          <LoggedUserDropdown/>
          <span
            @click="toggleFullScreen()"
            class="ml-3 w-11 h-11 cursor-pointer flex items-center justify-center overflow-hidden bg-blue-800 rounded-md hover:text-2xl duration-300 ease-in-out"
          >
            <i v-if="isFullScreen" class="pi pi-arrow-down-left-and-arrow-up-right-to-center"></i>
            <i v-else class="pi pi-arrow-up-right-and-arrow-down-left-from-center"></i>
          </span>
        </div>
      </div>
    </nav>
</template>