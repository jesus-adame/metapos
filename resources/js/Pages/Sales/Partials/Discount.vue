<script lang="ts" setup>
import Button from 'primevue/button';
import InputNumber from 'primevue/inputnumber';
import SelectButton from 'primevue/selectbutton';
import { computed, reactive, ref } from 'vue';

const selectedDiscountType = ref('percentage')
const emit = defineEmits(['apply'])
const form = reactive<{
    amount: number | null
}>({
    amount: null,
})

const discountTypes = ref([
    {label: 'Porcentaje', value: 'percentage'},
    {label: 'Monto', value: 'amount'},
]);

const prefixIcon = computed(() => {
    if (selectedDiscountType.value == 'percentage') {
        return '% ';
    }
    return ''
})

const addDiscount = () => {
    emit('apply', form.amount, selectedDiscountType.value)
}

const submitButton = computed(() => {
    if (form.amount == null) return true;
    return !(form.amount > 0);
})
</script>
<template>
    <div>
        <form @submit.prevent="addDiscount" class="grid gap-4">
            <div class="w-full text-center">
                <SelectButton v-model="selectedDiscountType" :options="discountTypes" option-label="label" option-value="value"></SelectButton>
            </div>
            <InputNumber v-model="form.amount" :min-fraction-digits="2" :max-fraction-digits="2" class="w-full" :prefix="prefixIcon" placeholder="0.00"></InputNumber>
            <div class="flex justify-end">
                <Button label="Aplicar" type="submit" :disabled="submitButton" raised></Button>
            </div>
        </form>
    </div>
</template>