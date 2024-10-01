<script lang="ts" setup>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import { onMounted, ref, watch } from 'vue';
import ExpenseService from "@/Services/ExpenseService";
import axios, { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';
import { can, formatDateTime, formatMoneyNumber, locationIcon } from '@/helpers';
import { Expense } from '@/types';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import { useExpenseStore } from '@/stores/ExpenseStore';
import CreateExpense from '../forms/CreateExpense.vue';
import EditExpense from '../forms/EditExpense.vue';
import UserIcon from '../icons/UserIcon.vue';
import LocationIcon from '../icons/LocationIcon.vue';

const expenseService = new ExpenseService()
const items = ref([])
const rows = ref(10)
const totalRecords = ref(0)
const page = ref(1)
const expenseStore = useExpenseStore()
const modalCreate = ref(false)
const modalEdit = ref(false)
const selectedExpense = ref<Expense | null>(null)
const confirm = useConfirm()
const toast = useToast()

const showModalCreate = () => {
    modalCreate.value = true
}

const hideModalCreate = () => {
    modalCreate.value = false
}

const showModalEdit = (category: Expense) => {
    modalEdit.value = true
    selectedExpense.value = category
}

const hideModalEdit = () => {
    modalEdit.value = false
    fetchItems(page.value)
}

const fetchItems = (pageNumber: number) => {
    const result = expenseService.paginate(pageNumber, rows.value)

    result.then((response: AxiosResponse) => {
        const expenses = response.data;

        totalRecords.value = expenses.total
        items.value = JSON.parse(JSON.stringify(expenses.data))
        page.value = expenses.current_page
    })
}

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchItems(pageNumber);
}

onMounted(() => {
    fetchItems(page.value)
})

watch(expenseStore.getExpenses, () => {
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
        message: 'Se eliminará el gasto',
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
    <Dialog v-model:visible="modalCreate" modal header="Nuevo gasto" :style="{ width: '35rem' }">
        <CreateExpense @save="hideModalCreate"></CreateExpense>
    </Dialog>

    <Dialog v-model:visible="modalEdit" modal header="Editar gasto" :style="{ width: '35rem' }">
        <EditExpense :expense="selectedExpense" @save="hideModalEdit"></EditExpense>
    </Dialog>

    <DataTable :value="items" class="shadow-md" :paginator="true" :rows="rows" :lazy="true" :totalRecords="totalRecords" @page="onPage">
        <Column field="id" header="#"></Column>
        <Column field="created_at" header="Fecha y hora">
            <template #body="{data}">
                {{ formatDateTime(data.created_at) }}
            </template>
        </Column>
        <Column field="description" header="Descripción">
            <template #body="{data}">
                {{ data.expense_category.name }}
            </template>
        </Column>
        <Column field="creator" header="Autor">
            <template #body="{data}">
                <UserIcon>
                    {{ data.creator.name }} {{ data.creator.lastname }}
                </UserIcon>
            </template>
        </Column>
        <Column field="location" header="Ubicación">
            <template #body="{data}">
                <LocationIcon :location="data.location">
                    <span>{{ data.location.name }}</span>
                </LocationIcon>
            </template>
        </Column>
        <Column field="amount" header="Monto">
            <template #body="{data}">
                {{ formatMoneyNumber(data.amount) }}
            </template>
        </Column>
        <Column field="" header="">
            <template #header>
                <div v-if="can('create expenses')" class="w-full flex justify-center">
                    <Button icon="pi pi-plus" rounded severity="success" raised @click="showModalCreate"></Button>
                </div>
            </template>
            <template #body="{data}">
                <div class="w-full flex gap-1 justify-center">
                    <Button raised v-if="can('update expenses')" @click="showModalEdit(data)" icon="pi pi-pencil" severity="warn"></Button>
                    <Button raised v-if="can('delete expenses')" icon="pi pi-trash" severity="danger" @click="confirmDelete(route('api.expenses.destroy', {expense: data.id}))"></Button>
                </div>
            </template>
        </Column>
    </DataTable>
</template>