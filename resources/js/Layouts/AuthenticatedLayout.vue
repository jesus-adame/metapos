<script setup lang="ts">
import { ref } from 'vue';
import SidebarMenu from '@/Components/SidebarMenu.vue';
import DynamicDialog from 'primevue/dynamicdialog';
import Toast from 'primevue/toast';
import SidebarMenuTest from '@/Components/SidebarMenuTest.vue';

// Aquí puedes agregar cualquier lógica o estados necesarios para el componente
const drawerSidevar = ref(false)

const openSidebar = () => {
    drawerSidevar.value = true
}

const closeSidebar = () => {
    drawerSidevar.value = false
}
</script>

<template>
    <SidebarMenu :sidebarVisible="drawerSidevar" @hideSidebar="closeSidebar" />
    <DynamicDialog />
    <Toast position="bottom-right" />

    <div class="min-h-screen bg-gray-100">
        <div class="layout-content flex">
            <aside>
                <SidebarMenuTest @openSidebar="openSidebar" />
            </aside>
            <!-- Contenido principal del dashboard -->
            <main>
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Estilos específicos de la página Dashboard */
aside {
    width: 80px;
    /* Otros estilos para el sidebar */
}
main {
    flex-grow: 1;
    padding: 20px;
    /* Otros estilos para el contenido principal */
}
</style>
