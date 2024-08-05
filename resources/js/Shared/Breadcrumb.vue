<script lang="ts" setup>
import LoggedUserDropdown from '@/Components/dropdowns/LoggedUserDropdown.vue';
import { Link } from '@inertiajs/vue3';
import Button from 'primevue/button';

function toggleFullScreen() {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen();
  } else if (document.exitFullscreen) {
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
              <li class="flex items-center text-blue-gray-900 antialiased font-sans text-sm font-normal leading-normal cursor-pointer transition-colors duration-300 hover:text-light-blue-500">
                <Link :href="route('dashboard')" class="block antialiased font-sans text-sm leading-normal text-blue-900 font-normal opacity-50 transition-all hover:text-blue-500 hover:opacity-100">
                  Panel
                </Link>
              </li>
              <li class="flex items-center text-blue-900 antialiased font-sans text-sm font-normal leading-normal pointer-events-none">
                <span class="text-gray-500 text-sm font-normal leading-normal mx-2 select-none">/</span>
                <p class="block antialiased text-sm leading-normal text-blue-gray-900 font-normal">home</p>
              </li>
            </ol>
          </nav>
        </div>
        <div class="flex items-center">
          <Button icon="pi pi-bell" class="mr-3" rounded severity="secondary"></Button>
          <LoggedUserDropdown/>
          <Button @click="toggleFullScreen()" icon="fi fi-bs-expand" class="ml-3 p-5" severity="info"></Button>
        </div>
      </div>
    </nav>
</template>