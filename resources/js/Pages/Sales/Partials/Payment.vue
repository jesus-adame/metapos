<script setup lang="ts">
import { formatMoneyNumber } from '@/helpers';
import axios from 'axios';
import Button from 'primevue/button';
import { computed, ref } from 'vue';

const emit = defineEmits(['cancel', 'save']);
const selectedPayment = ref('Efectivo');
const saleStatus = ref('pending');
const modalTicket = ref<boolean>(false)
const saleId = ref<number | null>()

const props = defineProps<{
  totalSale: number,
  form: any,
}>();

const modalResponse = ref(false);
const dialogResponseData = ref({
    content: null,
    message: null
})

const cancelPayment = () => {
  emit('cancel');
};

const submitPayment = () => {
  saleStatus.value = 'pending';
  emit('save');
};

const payments = ref([
  { icon: 'pi-money-bill', label: 'Efectivo', method: 'cash', amount: null, hasComision: false, comision: null },
  { icon: 'pi-credit-card', label: 'Tarjeta', method: 'card', amount: null, hasComision: false, comision: null },
  { icon: 'pi-arrow-right', label: 'Transferencia', method: 'transfer', amount: null, hasComision: false, comision: null },
]);

const paymentMethods = ref([
  'Efectivo',
  'Tarjeta',
  'Transferencia',
  'Mixto',
]);

const change = computed(() => {
  const saleTotal = props.totalSale;
  const cashPayment = totalCashPayment.value;
  const otherPayments = totalPayment.value - cashPayment;
  const remainingBalance = saleTotal - otherPayments;

  return cashPayment > remainingBalance ? cashPayment - remainingBalance : 0;
});

const totalPayment = computed(() => {
  return payments.value.reduce((acc, payment) => acc + (payment.amount || 0), 0);
});

const totalCashPayment = computed(() => {
  return payments.value.filter(payment => payment.method === 'cash')
                       .reduce((acc, payment) => acc + (payment.amount || 0), 0);
});

const clearPayments = () => {
  payments.value = [
    { icon: 'pi-money-bill', label: 'Efectivo', method: 'cash', amount: null, hasComision: false, comision: null },
    { icon: 'pi-credit-card', label: 'Tarjeta', method: 'card', amount: null, hasComision: false, comision: null },
    { icon: 'pi-arrow-right', label: 'Transferencia', method: 'transfer', amount: null, hasComision: false, comision: null },
  ];
}

const applyPayment = () => {
  const formData = props.form;

  formData.payment_methods = payments.value;

  axios.post(route('api.sales.store'), formData)
  .then(response => {
    const data = response.data

    saleStatus.value = 'paid';
    saleId.value = data.sale.id
    dialogResponseData.value = data;
    modalResponse.value = true
  })
  .catch(({response}) => {
    dialogResponseData.value = response.data
    modalResponse.value = true
  })
};

const closeDialogResponse = () => {
  modalResponse.value = false
  dialogResponseData.value = {
    content: null,
    message: null
  }

  if (saleStatus.value == 'paid') {
    submitPayment();
  }
}

const openModaTicket = () => {
    modalTicket.value = true
}
</script>

<template>
    <Dialog v-model:visible="modalTicket" modal :header="'Venta #' + saleId">
      <PdfObject :url="route('sales.ticket', {id: saleId})" :options="{ height: '100vh', width: '30vw', border: '1px', solid: '#ccc' }" />
    </Dialog>

  <Dialog v-model:visible="modalResponse" modal header="Completado" :closable="false">
    <Message class="mt-2" :closable="false" severity="info">{{ dialogResponseData.message }}</Message>
    <p class="my-4">
      <strong>{{ dialogResponseData.content }}</strong>
    </p>
    <div class="flex w-full">
      <Button v-if="saleStatus == 'paid'" class="ml-auto" @click="openModaTicket" severity="info">Imprimir ticket</Button>
      <Button class="ml-auto" @click="closeDialogResponse">Aceptar</Button>
    </div>
  </Dialog>

  <div class="w-full text-center mt-2">
    <SelectButton v-model="selectedPayment" :options="paymentMethods" aria-labelledby="basic" @change="clearPayments" />
  </div>
  <div class="w-full shadow-sm rounded-md px-3 mt-2">
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
            :max="(payment.label === 'Efectivo' || payment.label === 'Mixto') ? undefined : props.totalSale"
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
    <div class="flex justify-end flex-wrap w-full text-right">
      <div class="mb-6">
        <div class="mt-6 text-2xl font-bold">
          <span class="mr-4">Saldo</span>
          <span>{{ formatMoneyNumber(totalSale) }}</span>
        </div>
        <div class="text-2xl font-bold" v-if="selectedPayment == 'Mixto' || selectedPayment == 'Efectivo'">
          <span class="mr-4">Cambio</span>
          <span>{{ formatMoneyNumber(change) }}</span>
        </div>
      </div>
    </div>
    <div class="flex justify-end">
      <Button @click="applyPayment" class="mr-1" severity="success" icon="pi pi-dollar" label="PAGAR"></Button>
      <Button @click="cancelPayment" label="CANCELAR"></Button>
    </div>
  </div>
</template>