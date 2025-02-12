<template>
    <div class="w-full mx-auto p-5 space-y-3">
        <table class="table-auto ml-4">
            <tbody>
                <tr class="">
                    <th class="py-2.5 text-left">Title: </th>
                    <td class="pl-1.5">{{ form.title }}</td>
                </tr>
                <tr>
                    <th class="py-2.5 text-left">Description: </th>
                    <td class="pl-1.5 break-word">{{ form.description }}</td>
                </tr>
                <tr>
                    <th class="py-2.5 text-left">Amount: </th>
                    <td class="pl-1.5">&pound;{{ form.saving_amount }}</td>
                </tr>
                <tr>
                    <th class="py-2.5 text-left">Duration: </th>
                    <td class="pl-1.5">{{ form.savings_duration }}</td>
                </tr>
                <tr>
                    <th class="py-2.5 text-left">Total Members: </th>
                    <td class="pl-1.5">{{ form.total_members }}</td>
                </tr>
                <tr>
                    <th class="py-2.5 text-left">Payment Frequency: </th>
                    <td class="pl-1.5">{{ form.payment_frequency }}</td>
                </tr>
                <tr>
                    <th class="py-2.5 text-left">Penalty Rate: </th>
                    <td class="pl-1.5">&pound;{{ form.penalty_rate }}</td>
                </tr>
                <tr>
                    <th class="py-2.5 text-left">Payout Turn: </th>
                    <td class="pl-1.5">{{ form.payoutTurnMethod }}</td>
                </tr>
                <tr>
                    <th class="py-2.5 text-left">Start Date: </th>
                    <td class="pl-1.5">{{ form.start_date }}</td>
                </tr>
                <tr>
                    <th class="py-2.5 text-left">End Date: </th>
                    <td class="pl-1.5">{{ form.end_date }}</td>
                </tr>
            </tbody>
        </table>

        <div class="p-2 w-full mb-3">
            <CustomCheckBox v-model="form.checked" label="I agree to the Terms and Conditions" />
        </div>
        <!-- {{ props.termsError }} -->
        <!-- <ErrorAlert :showError="v$.checked.$error" :errorMessage="v$.checked.$errors[0]?.$message" /> -->
        <div v-if="localTermError" class="flex items-center space-x-6 p-3 mb-2 w-full bg-red-200 text-red-700 text-sm rounded-md" >
            <span><BxSolidError class="text-xl" /></span> 
            <span>Please accept our terms and conditions</span>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, numeric, between, helpers } from '@vuelidate/validators';
import { BxSolidError } from '@kalimahapps/vue-icons';
import CustomCheckBox from '@/CustomComponents/CustomCheckBox.vue';
import ErrorAlert from '@/CustomComponents/FormErrorAlert.vue'; 

// import TextField from '@/CustomComponents/TextField.vue';
// import Select from '@/CustomComponents/CustomSelects.vue';

const props = defineProps({
    form: Object,
    termsError: Boolean
})

const emit = defineEmits(['update:modelValue']);

const localForm = ref({ ...props.form }); 
let localTermError = ref({ ...props.termError });

const payoutTurnMethods = ref([])

// const termsError = ref(false)



const rules = computed(() => ({
    checked: {
        required: helpers.withMessage('Please accept our Terms and Conditions', required), 
    },
}));

const v$ = useVuelidate(rules, props.form);

// const validate = async () => {
//   v$.value.$touch();
//   return !v$.value.$invalid;
// };

watch(
  () => props.form.checked,
  (newVal) => {
    if(newVal == true){
        localTermError = false;    
    }else{
        localTermError = true;
    }
  },
  { deep: true }
);


const paymentFreqOptions = ref([
    { value: 'Weekly', label: 'Weekly' },
    { value: 'Monthly', label: 'Monthly' },
])


const payout_turns = () => {
    axios.get('/get_payment_turn_methods').then((res) => {
        payoutTurnMethods.value = res.data
    })
}


const selectedPayoutMethodDetails = computed(() => {
  return payoutTurnMethods.value.find(
    (method) => method.id === props.form.payout_turn
  );
});


const selectPayoutTurnMethod = (res) => {
    console.log(res)
}





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

onMounted(() => {
    // document.addEventListener("click", handleClickOutside);
    payout_turns();
});
</script>