<script setup lang="ts">
import { formatMoneyNumber } from '@/helpers';
import LocationService from '@/Services/LocationService';
import { Location, Supplier } from '@/types';
import axios from 'axios';
import { AxiosResponse } from 'axios';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';
import DatePicker from 'primevue/datepicker';
import { onMounted, ref } from 'vue';

const emit = defineEmits(['cancel', 'save']);
const purchaseStatus = ref('pending');
const locations = ref<Location[]>([]);
const locationService = new LocationService();
const updateCashRegister = ref(false)
const selectedPayment = ref('Efectivo');

const props = defineProps<{
  totalPurchase: number,
  form: any,
  supplier: Supplier | undefined,
}>();

const modalResponse = ref(false);
const dialogResponseData = ref({
    type: 'success',
    header: undefined,
    message: null,
    content: ''
})

const cancelPayment = () => {
  emit('cancel');
};

const submitPayment = () => {
  purchaseStatus.value = 'pending';
  emit('save');
};

const applyPayment = () => {
  const formData = props.form?.data();
  formData.supplier_id = props.supplier?.id;
  formData.update_cash_register = updateCashRegister.value;
  formData.payment_methods = payments.value;

  axios.post(route('api.purchases.store'), formData)
  .then(response => {
    purchaseStatus.value = 'completed';
    openDialogResponse({
      type: 'success',
      header: 'Correcto',
      message: response.data.message,
      content: ''
    });
  })
  .catch(({response}) => {
    openDialogResponse({
      type: 'error',
      header: 'Error',
      message: response.data.message,
      content: ''
    });
  })
};

const openDialogResponse = (data: any) => {
  modalResponse.value = true
  dialogResponseData.value = data
}

const closeDialogResponse = () => {
  modalResponse.value = false
  dialogResponseData.value = {
    type: 'success',
    header: undefined,
    message: null,
    content: ''
  }

  if (purchaseStatus.value == 'completed') {
    submitPayment();
  }
}

const fetchLocations = () => {
    locationService.fetchAll()
    .then((response: AxiosResponse) => {
        const paginate = response.data
        locations.value = paginate.data
    })
}

onMounted(() => {
    fetchLocations()
})

const paymentMethods = ref([
  'Efectivo',
  'Tarjeta',
  'Transferencia',
  'Mixto',
]);

const payments = ref([
  { icon: 'pi-money-bill', label: 'Efectivo', method: 'cash', amount: null, hasComision: false, comision: null },
  { icon: 'pi-credit-card', label: 'Tarjeta', method: 'card', amount: null, hasComision: false, comision: null },
  { icon: 'pi-arrow-right', label: 'Transferencia', method: 'transfer', amount: null, hasComision: false, comision: null },
]);

const clearPayments = () => {
  payments.value = [
    { icon: 'pi-money-bill', label: 'Efectivo', method: 'cash', amount: null, hasComision: false, comision: null },
    { icon: 'pi-credit-card', label: 'Tarjeta', method: 'card', amount: null, hasComision: false, comision: null },
    { icon: 'pi-arrow-right', label: 'Transferencia', method: 'transfer', amount: null, hasComision: false, comision: null },
  ];
}
</script>

<template>
    <Dialog v-model:visible="modalResponse" modal :header="dialogResponseData.header" :closable="false">
        <Message class="mt-2" :closable="false" :severity="dialogResponseData.type">{{ dialogResponseData.message }}</Message>
        <p class="mt-2">
            <strong>{{ dialogResponseData.content }}</strong>
        </p>
        <div class="flex w-full">
            <Button class="ml-auto" @click="closeDialogResponse">Aceptar</Button>
        </div>
    </Dialog>
    <div class="w-full shadow-sm rounded-md px-3 mt-2">
        <div>
            <label for="location_id" class="block">Ubicación</label>
            <Select v-model="form.location_id" :options="locations" optionLabel="name" optionValue="id" class="w-full"></Select>
        </div>
        <label for="applicated_at">Fecha de compra</label>
        <DatePicker class="d-block w-full" date-format="dd/mm/yy" v-model="form.applicated_at"></DatePicker>
        <div class="flex items-center my-4">
            <Checkbox v-model="updateCashRegister" :binary="true" inputId="cash" class="mr-2"/>
            <label for="cash">Pagar con Caja</label>
        </div>
        <div v-if="updateCashRegister" id="cashRegisterPayment">
            <SelectButton v-model="selectedPayment" :options="paymentMethods" aria-labelledby="basic" @change="clearPayments" />
            <div v-for="(payment, index) in payments" :key="index" class="flex w-full items-center">
                <div v-if="selectedPayment == payment.label || selectedPayment == 'Mixto'" class="w-full">
                    <div class="flex items-center justify-between">
                        <span class="mr-4 flex items-center">
                            <i :class="payment.icon" class="pi px-4 pt-3 pb-2 text-2xl text-blue-400"></i>
                            <strong>{{ payment.label }}</strong>
                        </span>
                        <InputNumber
                            v-model="payment.amount"
                            required
                            :min="0"
                            :max="props.totalPurchase"
                            class="my-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-60"
                            mode="currency"
                            currency="MXN"
                            placeholder="$0.00"
                            showButtons
                        />
                    </div>
                    <!-- TODO: Comision por pago con tajeta -->
                    <!-- <div class="flex items-center justify-between">
                    <div class="flex my-2" v-if="payment.label === 'Tarjeta'">
                        <span id="switch2" class="mr-3">Agregar comisión</span>
                        <ToggleSwitch v-model="payment.hasComision"></ToggleSwitch>
                    </div>
                    <div v-if="payment.hasComision">
                        <InputNumber class="w-60" v-model="payment.comision" placeholder="%0.0" prefix="%" showButtons></InputNumber>
                    </div>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="flex justify-end flex-wrap w-full text-right">
            <div class="mb-6">
                <div class="mt-6 text-2xl font-bold">
                    <span class="mr-4">Saldo</span>
                    <span>{{ formatMoneyNumber(totalPurchase) }}</span>
                </div>
            </div>
        </div>
        <div class="flex justify-end">
            <Button @click="applyPayment" class="mr-1" severity="success" icon="pi pi-dollar" label="PAGAR"></Button>
            <Button @click="cancelPayment" label="CANCELAR"></Button>
        </div>
    </div>
</template>