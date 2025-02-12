<template>
    <div class="w-full mx-auto p-5 space-y-3">
        <div class="p-2 w-full space-y-2">
            <div class="text-gray-600 text-md pb-2">3. How many members will be part of this savings? This should normally be equal to the savings duration</div>
            <TextField v-model="form.total_members" :rows="1" placeholder="Example: 10" @blur="v$.total_members.$touch()"  />
            <ErrorAlert :showError="v$.total_members.$error" :errorMessage="v$.total_members.$errors[0]?.$message" />
        </div> 
        <div class="p-2 w-full space-y-2">
            <div class="text-gray-600 text-md pb-2">4. What is the savings duration (in months or weeks)</div>
            <TextField v-model="form.savings_duration" :rows="1" placeholder="Example: 10" @blur="v$.savings_duration.$touch()"  />
            <ErrorAlert :showError="v$.savings_duration.$error" :errorMessage="v$.savings_duration.$errors[0]?.$message" />
        </div>
        <div class="p-2 w-full space-y-2">
            <div class="text-gray-600 text-md pb-2">5. How much is each member contributing?</div>
            <TextField v-model="form.saving_amount" :rows="1" placeholder="Example: 100" @blur="v$.saving_amount.$touch()"  />
            <ErrorAlert :showError="v$.saving_amount.$error" :errorMessage="v$.saving_amount.$errors[0]?.$message" />
        </div>
        <div class="p-2 w-full space-y-2">
            <div class="text-blue-700 text-md pb-2 bg-blue-100 border border-gray-100 px-3 py-2.5 rounded-md">Total Payout will be &pound;{{ form.total_members * form.saving_amount }}</div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, numeric, between, helpers } from '@vuelidate/validators';
import TextField from '@/CustomComponents/TextField.vue';
import ErrorAlert from '@/CustomComponents/FormErrorAlert.vue'; 

const props = defineProps({
    form: Object
})

const emit = defineEmits(['update:modelValue']);

const localForm = ref({ ...props.form }); 

// Custom validation for positive integers
const positiveInteger = helpers.withMessage(
  "Please enter a positive integer",
  (value) => Number.isInteger(Number(value)) && Number(value) > 0
);

const rules = computed(() => ({
    total_members: {
        required: helpers.withMessage('The Total members field is required', required), 
        numeric: helpers.withMessage('Only numeric values are allowed', numeric), 
        positiveInteger
    },
    savings_duration: {
        required: helpers.withMessage('The Savings duration field is required', required), 
        numeric: helpers.withMessage("Only numeric values are allowed", numeric),
        positiveInteger,
    },
    saving_amount: {
        required: helpers.withMessage('The Saving amount field is required', required), 
        numeric: helpers.withMessage('Only numeric values are allowed', numeric), 
        between: helpers.withMessage('You can only enter an amount between £1 and £1000', between(1, 1000)) 
    }
}));

const v$ = useVuelidate(rules, props.form);

const validate = async () => {
  const result = await v$.value.$validate();
  emit('update:modelValue', localForm);
  return result;
};

// Emit updates to the parent when data changes
watch(
  () => props.form,
  (newVal) => {
    localForm.value = {...newVal};
  },
  { deep: true }
);

defineExpose({ validate });
</script>