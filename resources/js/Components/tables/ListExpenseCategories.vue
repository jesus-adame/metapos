<script lang="ts" setup>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import { onMounted, ref, watch } from 'vue';
import ExpenseCategoryService from "@/Services/ExpenseCategoryService";
import axios, { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';
import { can, formatDate } from '@/helpers';
import { ExpenseCategory } from '@/types';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { useExpenseCategoryStore } from '@/stores/ExpenseCategoryStore';
import CreateExpenseCategory from '../forms/CreateExpenseCategory.vue';
import EditExpenseCategory from '../forms/EditExpenseCategory.vue';

const expenseCategoryService = new ExpenseCategoryService()
const items = ref([])
const rows = ref(10)
const totalRecords = ref(0)
const page = ref(1)
const categoryStore = useExpenseCategoryStore()
const modalCreate = ref(false)
const modalEdit = ref(false)
const selectedCategory = ref<ExpenseCategory | null>(null)
const confirm = useConfirm()
const toast = useToast()

const showModalCreate = () => {
    modalCreate.value = true
}

const hideModalCreate = () => {
    modalCreate.value = false
}

const showModalEdit = (category: ExpenseCategory) => {
    modalEdit.value = true
    selectedCategory.value = category
}

const hideModalEdit = () => {
    modalEdit.value = false
    fetchItems(page.value)
}

const fetchItems = (pageNumber: number) => {
    const result = expenseCategoryService.paginate(pageNumber, rows.value)

    result.then((response: AxiosResponse) => {
        const expense_categories = response.data;

        totalRecords.value = expense_categories.total
        items.value = JSON.parse(JSON.stringify(expense_categories.data))
        page.value = expense_categories.current_page
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
        message: 'Se eliminará la categoría',
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
    <Dialog v-model:visible="modalCreate" modal header="Nueva categoría" :style="{ width: '35rem' }">
        <CreateExpenseCategory @save="hideModalCreate"></CreateExpenseCategory>
    </Dialog>

    <Dialog v-model:visible="modalEdit" modal header="Editar categoría" :style="{ width: '35rem' }">
        <EditExpenseCategory :category="selectedCategory" @save="hideModalEdit"></EditExpenseCategory>
    </Dialog>

    <DataTable :value="items" class="shadow-md" :paginator="true" :rows="rows" :lazy="true" :totalRecords="totalRecords" @page="onPage">
        <Column field="id" header="#"></Column>
        <Column field="name" header="Nombre"></Column>
        <Column field="description" header="Descripción"></Column>
        <Column field="created_at" header="Creación">
            <template #body="{data}">
                {{ formatDate(data.created_at) }}
            </template>
        </Column>
        <Column field="" header="">
            <template #header>
                <div v-if="can('create categories')" class="w-full flex justify-center">
                    <Button icon="pi pi-plus" rounded severity="success" raised @click="showModalCreate"></Button>
                </div>
            </template>
            <template #body="{data}">
                <div class="w-full flex gap-1 justify-center">
                    <Button raised v-if="can('update categories')" @click="showModalEdit(data)" icon="pi pi-pencil" severity="warn"></Button>
                    <Button raised v-if="can('delete categories')" icon="pi pi-trash" severity="danger" @click="confirmDelete(route('api.expense_categories.destroy', {expense_category: data.id}))"></Button>
                </div>
            </template>
        </Column>
    </DataTable>
</template>