<template>
    <div class="w-full mx-auto p-5 space-y-3">
        <div class="p-2 w-full space-y-2">
            <div class="text-gray-600 text-md pb-2">6. What is the payment frequency, weekly or monthly?</div>
            <Select v-model="form.payment_frequency" :options="paymentFreqOptions"  placeholder="Select Payment Frequency" labelField="label" value="value" @blur="v$.payment_frequency.$touch()">
                <template #option="{ option }">
                    <div class="flex items-center">
                        <span class="font-semibold">{{ option.label }}</span>
                    </div>
                </template>
            </Select>
            <ErrorAlert :showError="v$.payment_frequency.$error" :errorMessage="v$.payment_frequency.$errors[0]?.$message" />
        </div> 
        <div class="p-2 w-full space-y-2">
            <div class="text-gray-600 text-md pb-2">7. Select The Payout Turn Method</div>
            <Select v-model="form.payout_turn" :options="payoutTurnMethods"  placeholder="Select Payout Turns Method" labelField="name" valueField="id" @blur="v$.payout_turn.$touch()" @update:modelValue="selectPayoutTurnMethod" @option-selected="handlePayoutOptionSelected">
                <template #option="{ option }">
                    <div class="flex items-center">
                        <span class="font-semibold">{{ option.name }}</span>
                    </div>
                </template>
            </Select>
            <div v-if="selectedPayoutMethodDetails" class="my-3 w-full bg-blue-100 text-blue-700 rounded-md text-sm p-4 leading-6 space-y-2">
                <div><span class="font-semibold tracking-wide">Description: </span> <span>{{ selectedPayoutMethodDetails.description }}</span> </div>
                <div><span class="font-semibold tracking-wide">Benefits: </span> <span>{{ selectedPayoutMethodDetails.benefits }}</span> </div>
            </div>
            <ErrorAlert :showError="v$.payout_turn.$error" :errorMessage="v$.payout_turn.$errors[0]?.$message" />
        </div>
        <div class="p-2 w-full space-y-2">
            <div class="text-gray-600 text-md pb-2 w-full">8. When a member misses payment by more than 24 hours, how much penalty do you want them to pay? <span class="relative"><button class="bg-transparent cursor-pointer" @mouseenter="showPenaltyInfo" @click="togglePenaltyInfo"><IoOutlineInformationCircle class="text-xl text-gray-blue-700" /></button>
                <div v-if="penaltyInfo" ref="penaltyCont" class="absolute bottom-0 right-4 w-full md:min-w-[30rem] md:min-h-20 overflow-hidden bg-blue-100 text-blue-700 p-4 leading-6 tracking-normal text-sm border border-gray-200 rounded-lg shadow-md z-10">
                    This is entirely up to the group creator to set. If you do not want to set a penalty fee, then leave the field blank. See details about penalty fee in our <Link :href="route('faq')" class="text-blue-800 font-semibold underline">FAQ page </Link>
                </div>
            </span></div>
            <TextField v-model="form.penalty_rate" :rows="1" placeholder="Example: 10" @blur="v$.penalty_rate.$touch()" />
            <ErrorAlert :showError="v$.penalty_rate.$error" :errorMessage="v$.penalty_rate.$errors[0]?.$message" />
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { Link } from '@inertiajs/vue3';
import { IoOutlineInformationCircle } from '@kalimahapps/vue-icons';
import { required, numeric, between, helpers } from '@vuelidate/validators';
import TextField from '@/CustomComponents/TextField.vue';
import Select from '@/CustomComponents/CustomSelects.vue';
import ErrorAlert from '@/CustomComponents/FormErrorAlert.vue'; 

const props = defineProps({
    form: Object
})

const emit = defineEmits(['update:modelValue', 'selectedPayoutOrder']);

const localForm = ref({ ...props.form }); 

const payoutTurnMethods = ref([])

const penaltyInfo = ref(false);
const penaltyCont = ref(null);


const paymentFreqOptions = ref([
    { value: 'Weekly', label: 'Weekly' },
    { value: 'Monthly', label: 'Monthly' },
])


const payout_turns = () => {
    axios.get('/get_payment_turn_methods').then((res) => {
        payoutTurnMethods.value = res.data
    })
}


const showPenaltyInfo = () => {
    penaltyInfo.value = true;
}

const togglePenaltyInfo = () => {
    penaltyInfo.value =!penaltyInfo.value;
}

const selectedPayoutMethodDetails = computed(() => {
  return payoutTurnMethods.value.find(
    (method) => method.id === props.form.payout_turn
  );
});


const selectPayoutTurnMethod = (option) => {
    // console.log(res)
    emit('update:modelValue', {...localForm.value, payout_turn: option });
}

const handlePayoutOptionSelected = (option) => {
    emit('selectedPayoutOrder', option.name)
}


const rules = computed(() => ({
    payment_frequency: {
        required: helpers.withMessage('Choose a Payment Frequency', required), 
    },
    payout_turn: {
        required: helpers.withMessage('Select a Payout Order method', required), 
    },
    penalty_rate: {
        required: helpers.withMessage('The Penalty rate field is required', required), 
        numeric: helpers.withMessage('Only numeric values are allowed', numeric), 
        between: helpers.withMessage('You can only enter an amount between 0 and 50', between(0, 50)) 
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


const handleClickOutside = (event) => {
      // Check if the click happened outside the menu
      if (penaltyCont.value && !penaltyCont.value.contains(event.target)) {
            penaltyInfo.value = false; // Close the menu
      }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
    payout_turns();
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>