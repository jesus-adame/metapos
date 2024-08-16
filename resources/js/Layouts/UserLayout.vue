<script setup lang="ts">
import Sidebar from '@/Components/Sidebar.vue';
import Breadcrumb from '@/Shared/Breadcrumb.vue';
import ConfirmDialog from 'primevue/confirmdialog';
import DynamicDialog from 'primevue/dynamicdialog';
import Toast from 'primevue/toast';
</script>

<template>
  <!-- component -->
  <div class="min-h-screen bg-gray-100">
    <Sidebar></Sidebar>
    <div class="py-4 px-10 xl:ml-10">
      <Breadcrumb>
        <slot name="header" />
      </Breadcrumb>

      <!-- Page Content -->
      <main>
          <slot />
      </main>

      <DynamicDialog />
      <Toast position="top-right" />
      <ConfirmDialog></ConfirmDialog>
    </div>
  </div>
</template>