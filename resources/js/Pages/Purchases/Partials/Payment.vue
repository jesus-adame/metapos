<script setup>
import { formatMoneyNumber } from '@/helpers';
import axios from 'axios';
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import { ref } from 'vue';

const emit = defineEmits(['cancel', 'save']);
const purchaseStatus = ref('pending');

const props = defineProps({
  totalPurchase: Number,
  form: Object,
  supplier: Object,
});

const modalResponse = ref(false);
const dialogResponseData = ref({
    type: null,
    header: null,
    message: null
})

const cancelPayment = () => {
  emit('cancel');
};

const submitPayment = () => {
  purchaseStatus.value = 'pending';
  emit('save');
};

const applyPayment = () => {
  const formData = props.form.data();
  formData.supplier_id = props.supplier?.id;

  axios.post(route('api.purchases.store'), formData)
  .then(response => {
    purchaseStatus.value = 'paid';
    openDialogResponse({
      type: 'success',
      header: 'Correcto',
      message: response.data.message
    });
  })
  .catch(({response}) => {
    openDialogResponse({
      type: 'error',
      header: 'No se pudo registrar',
      message: response.data.message,
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
    message: null
  }

  if (purchaseStatus.value == 'paid') {
    submitPayment();
  }
}
</script>

<template>
  <Dialog v-model:visible="modalResponse" modal :header="dialogResponseData.header">
    <Message class="mt-2" :closable="false" :severity="dialogResponseData.type">{{ dialogResponseData.header }}</Message>
    <p class="mt-2">
      <strong>{{ dialogResponseData.message }}</strong>
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