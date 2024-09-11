<script lang="ts" setup>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import { onMounted, ref, watch } from 'vue';
import CategoryService from "@/Services/CategoryService";
import axios, { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';
import CreateCategory from '../forms/CreateCategory.vue';
import { can, formatDate } from '@/helpers';
import UserIcon from '../icons/UserIcon.vue';
import EditCategory from '../forms/EditCategory.vue';
import { Category } from '@/types';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { useCategoryStore } from '@/stores/CategoryStore';

const categoryService = new CategoryService()
const items = ref([])
const rows = ref(10)
const totalRecords = ref(0)
const page = ref(1)
const categoryStore = useCategoryStore()
const modalCreate = ref(false)
const modalEdit = ref(false)
const selectedCategory = ref<Category | null>(null)
const confirm = useConfirm()
const toast = useToast()

const showModalCreate = () => {
    modalCreate.value = true
}

const hideModalCreate = () => {
    modalCreate.value = false
}

const showModalEdit = (category: Category) => {
    modalEdit.value = true
    selectedCategory.value = category
}

const hideModalEdit = () => {
    modalEdit.value = false
    fetchItems(page.value)
}

const fetchItems = (pageNumber: number) => {
    const result = categoryService.paginate(pageNumber, rows.value)

    result.then((response: AxiosResponse) => {
        const categories = response.data;

        totalRecords.value = categories.total
        items.value = JSON.parse(JSON.stringify(categories.data))
        page.value = categories.current_page
    })
}

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchItems(pageNumber);
}

onMounted(() => {
    fetchItems(page.value)
})

watch(categoryStore.getCategories, () => {
    fetchItems(page.value)
})

const deleteItem = (url: string) => {
    axios.post(url, { _method: 'delete' })
    .then((response: AxiosResponse) => {
        console.log(response);
        toast.add({ summary: 'Correcto', detail: response.data.message, severity: 'success', life: 1500 })
        fetchItems(page.value)
    })
    .catch(reject => {
        console.error(reject.response.data.errors);
        toast.add({ summary: 'Error', detail: reject.response.data.message, severity: 'error', life: 3000 })
    })
}

const confirmDelete = (url: string) => {
    confirm.require({
        header: '¿Está seguro?',
        message: 'Se eliminará el categoría',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancelar',
            severity: 'secondary',
        },
        acceptProps: {
            label: 'Eliminar',
            severity: 'danger'
        },
        accept: () => {
            deleteItem(url)
        }
    });
}
</script>
<template>
    <Dialog v-model:visible="modalCreate" modal header="Nuevo categoría" :style="{ width: '35rem' }">
        <CreateCategory @save="hideModalCreate"></CreateCategory>
    </Dialog>

    <Dialog v-model:visible="modalEdit" modal header="Editar categoría" :style="{ width: '35rem' }">
        <EditCategory :category="selectedCategory" @save="hideModalEdit"></EditCategory>
    </Dialog>

    <DataTable :value="items" class="shadow-md" :paginator="true" :rows="rows" :lazy="true" :totalRecords="totalRecords" @page="onPage">
        <Column field="id" header="#"></Column>
        <Column field="name" header="Nombre">
            <template #body="{data}">
                <UserIcon>
                    {{ data.name }}
                </UserIcon>
            </template>
        </Column>
        <Column field="description" header="Descripción"></Column>
        <Column field="created_at" header="Creación">
            <template #body="slot">
                {{ formatDate(slot.data.created_at) }}
            </template>
        </Column>
        <Column field="updated_at" header="Edición">
            <template #body="slot">
                {{ formatDate(slot.data.updated_at) }}
            </template>
        </Column>
        <Column field="" header="">
            <template #header>
                <div class="w-full flex justify-center">
                    <Button icon="pi pi-plus" rounded severity="success" raised @click="showModalCreate"></Button>
                </div>
            </template>
            <template #body="{data}">
                <div class="w-full flex gap-1 justify-center">
                    <Button raised v-if="can('update categories')" @click="showModalEdit(data)" icon="pi pi-pencil" severity="warn"></Button>
                    <Button v-if="can('delete categories')" icon="pi pi-trash" severity="danger" @click="confirmDelete(route('api.categories.destroy', {category: data.id}))"></Button>
                </div>
            </template>
        </Column>
    </DataTable>
</template>