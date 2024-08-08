<script setup>
import { formatMoneyNumber } from '@/helpers';
import axios from 'axios';
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import { computed, ref } from 'vue';

const emit = defineEmits(['cancel', 'save']);
const selectedPayment = ref('Efectivo');
const saleStatus = ref('pending');

const props = defineProps({
  totalPurchase: Number,
  form: Object,
});

const modalResponse = ref(false);
const dialogResponseData = ref({
    type: null,
    header: null,
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
  const totalPurchase = props.totalPurchase;
  const cashPayment = totalCashPayment.value;
  const otherPayments = totalPayment.value - cashPayment;
  const remainingBalance = totalPurchase - otherPayments;

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
  const formData = props.form.data();

  formData.payment_methods = payments.value;

  axios.post(route('purchases.store'), formData)
  .then(response => {
    saleStatus.value = 'paid';
    openDialogResponse({
      type: 'success',
      header: 'Correcto',
      message: response.data.message,
      content: response.data.content
    });
  })
  .catch(({response}) => {
    openDialogResponse({
      type: 'error',
      header: 'No se pudo registrar',
      message: response.data.message,
      content: null
    });
  })
};

const openDialogResponse = (data) => {
  modalResponse.value = true
  dialogResponseData.value = data
}

const closeDialogResponse = () => {
  modalResponse.value = false
  dialogResponseData.value = {
    type: null,
    header: null,
    content: null,
    message: null
  }

  if (saleStatus.value == 'paid') {
    submitPayment();
  }
}
</script>

<template>
  <Dialog v-model:visible="modalResponse" modal :header="dialogResponseData.header">
    <Message :closable="false" :severity="dialogResponseData.type">{{ dialogResponseData.message }}</Message>
    <p class="mt-2">
      <strong>{{ dialogResponseData.content }}</strong>
    </p>
    <div class="flex w-full">
      <Button class="ml-auto" @click="closeDialogResponse">Aceptar</Button>
    </div>
  </Dialog>

  <div class="w-full shadow-sm rounded-md px-3 mt-2">
    <label for="purchase_date">Fecha de compra</label>
    <DatePicker class="d-block w-full" date-format="dd/mm/yy" v-model="form.purchase_date"></DatePicker>
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