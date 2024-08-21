<script lang="ts" setup>
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import CreateLocation from '@/Components/forms/CreateLocation.vue';
import { onMounted, ref } from 'vue';
import LocationService from "@/Services/LocationService";
import axios, { AxiosResponse } from 'axios';
import { DataTablePageEvent } from 'primevue/datatable';
import Tag from 'primevue/tag';
import { locationIcon } from '@/helpers';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from 'primevue/useconfirm';
import EditLocation from '../forms/EditLocation.vue';
import { Location } from '@/types';

const locationService: LocationService = new LocationService()
const items = ref([])
const rows = ref(10)
const totalRecords = ref(0)
const page = ref(1)
const confirm = useConfirm()
const toast = useToast()
const modalCreate = ref(false)
const modalEdit = ref(false)
const selectedLocation = ref<Location | null>(null)

const showModalCreate = () => {
    modalCreate.value = true
}

const hideModalCreate = () => {
    modalCreate.value = false
    fetchItems(page.value)
}

const showModalEdit = (location: Location) => {
    selectedLocation.value = location
    modalEdit.value = true
}

const hideModalEdit = () => {
    modalEdit.value = false
    fetchItems(page.value)
}

const fetchItems = (pageNumber: number) => {
    const result = locationService.paginate(pageNumber, rows.value)

    result.then((response: AxiosResponse) => {
        const locations = response.data;

        totalRecords.value = locations.total
        items.value = JSON.parse(JSON.stringify(locations.data))
        page.value = locations.current_page
    })
}

const onPage = (event: DataTablePageEvent) => {
    const pageNumber = event.first / event.rows + 1;
    fetchItems(pageNumber);
}

onMounted(() => {
    fetchItems(page.value)
})

const calculateTypeLabel = (type: string) => {
    switch (type) {
        case 'branch': {
            return 'Sucursal';
        }
        case 'warehouse': {
            return 'Almacen';
        }
    }
}

const deleteItem = (url: string) => {
    axios.post(url, { _method: 'delete' })
    .then((response: AxiosResponse) => {
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
        message: 'Se eliminará la ubicación y su contenido',
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
    <Dialog v-model:visible="modalCreate" modal header="Nueva ubicación" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <CreateLocation class="mt-4" @save="hideModalCreate"></CreateLocation>
    </Dialog>
    <Dialog v-model:visible="modalEdit" modal header="Editar ubicación" :style="{ width: '35rem' }" pt:mask:class="backdrop-blur-sm">
        <EditLocation class="mt-4" @save="hideModalEdit" :location="selectedLocation"></EditLocation>
    </Dialog>
    <DataTable :value="items" paginator lazy :rows="rows" @page="onPage" :totalRecords="totalRecords">
        <Column field="id" header="#"></Column>
        <Column field="name" header="Nombre">
            <template #body="slot">
                <div class="flex items-center">
                    <div class="py-2 px-3 bg-gray-200 rounded-full mr-3 text-gray-500">
                        <i :class="locationIcon(slot.data)"></i>
                    </div>
                    <div>
                        <span>{{ slot.data.name }}</span>
                        <Tag class="ml-2" v-if="slot.data.is_default" :value="'default'" severity="info"></Tag>
                    </div>
                </div>
            </template>
        </Column>
        <Column field="address" header="Ubicación"></Column>
        <Column field="type" header="Tipo">
            <template #body="slot">
                {{ calculateTypeLabel(slot.data.type) }}
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
                    <Button icon="pi pi-pencil" raised severity="warn" @click="showModalEdit(data)"></Button>
                    <Button icon="pi pi-trash" raised severity="danger" @click="confirmDelete(route('api.locations.destroy', {location: data.id}))"></Button>
                </div>
            </template>
        </Column>
    </DataTable>
</template>