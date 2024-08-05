<script lang="ts" setup>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import CreateBranch from '@/Components/forms/CreateBranch.vue';
import { onMounted, ref } from 'vue';
import BranchService from "@/Services/BranchService";
import { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';

const branchService: BranchService = new BranchService()
const items = ref([])
const rows = ref(10)
const totalRecords = ref(0)
const page = ref(1)

const modalCreate = ref(false)

const showModalCreate = () => {
    modalCreate.value = true
}

const hideModalCreate = () => {
    modalCreate.value = false
    fetchItems(page.value)
}

const fetchItems = (pageNumber: number) => {
    const result = branchService.paginate(pageNumber, rows.value)

    result.then((response: AxiosResponse) => {
        const branches = response.data;

        totalRecords.value = branches.total
        items.value = JSON.parse(JSON.stringify(branches.data))
        page.value = branches.current_page
    })
}

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchItems(pageNumber);
}

onMounted(() => {
    fetchItems(page.value)
})
</script>
<template>
    <DataTable :value="items" paginator lazy :rows="rows" @page="onPage" :totalRecords="totalRecords">
        <Column field="id" header="#"></Column>
        <Column field="name" header="Nombre"></Column>
        <Column field="address" header="Ubicación"></Column>
        <Column field="" header="">
            <template #header>
                <Button icon="pi pi-plus" rounded severity="success" raised @click="showModalCreate"></Button>
            </template>
        </Column>
    </DataTable>

    <Dialog v-model:visible="modalCreate" modal header="Registrar usuario" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <CreateBranch class="mt-4" @save="hideModalCreate"></CreateBranch>
    </Dialog>
</template>