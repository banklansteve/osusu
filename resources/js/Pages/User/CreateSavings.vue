<template>
    <Head title="Create Savings" />

    <AuthLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Savings</h2>
        </template>

        <div class="w-full md:w-1/2 mx-auto tracking-wide text-xl font-medium text-white px-2 py-2.5 bg-primary rounded-md mb-5 text-center"> Create New Saving - {{ stepCaptions[currentStep - 1] }}</div>
        <div class="w-[98%] md:w-1/2 mx-auto bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">
        <!-- <p class="text-gray-600 mt-5 text-lg px-2">{{ stepCaptions[currentStep - 1] }}</p> -->
            <component :is="currentStepComponent" :form="formData" ref="currentStepRef" @updateForm="updateForm"  @selectedPayoutOrder="setPayoutOption" :termsError="termsError"/>
            <div class="w-full mt-3 px-2 pb-4 space-x-4 flex justify-between items-center ">
                <div class="w-full flex justify-between items-center">
                    <button v-if="currentStep > 1" @click="prevStep" type="button" class="px-4 py-2 bg-transparent text-primary rounded-full hover:bg-primary hover:text-white transition-all ease-in-out duration-300 flex items-center justify-between"><AkArrowLeft class="text-xl" /><span class="ml-2">Previous</span></button>
                    <button v-if="currentStep < 5" @click="nextStep" type="button" class="px-4 py-2 bg-transparent text-primary rounded-full hover:bg-primary hover:text-white transition-all ease-in-out duration-300 flex items-center justify-between"> <span class="mr-2">Next</span><AkArrowRight class="text-xl" /></button>
                    <Button v-if="currentStep === 5" @click="handleSubmit" :isSubmitting="isSubmitting" class="flex items-center mr-3"><span>Create Savings</span><span v-if="isSubmitting" class="ml-2"><UiLoading class="animate-spin text-2xl" /></span></Button>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from 'vue';
import useVuelidate from '@vuelidate/core';
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout2.vue';
import CreateOne from '@/PageComponent/CreateOne.vue';
import CreateTwo from '@/PageComponent/CreateTwo.vue';
import CreateThree from '@/PageComponent/CreateThree.vue';
import CreateFour from '@/PageComponent/CreateFour.vue';
import CreateFive from '@/PageComponent/CreateFive.vue';
import { UiLoading, AkArrowLeft, AkArrowRight } from '@kalimahapps/vue-icons';
import TextField from '@/CustomComponents/TextField.vue';
import Select from '@/CustomComponents/CustomSelects.vue';
import CustomCheckBox from '@/CustomComponents/CustomCheckBox.vue';
import Button from '@/CustomComponents/FlatSubmitBtn.vue'
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';


const formData = useForm({
    title: null,
    description: null,
    saving_amount: null,
    savings_duration: null,
    total_members: null,
    payment_frequency: null,
    penalty_rate: null,
    payout_turn: null,
    start_date: null,
    end_date: null,
    payoutTurnMethod: null,
    checked: false
});

const components = [CreateOne, CreateTwo, CreateThree, CreateFour, CreateFive];

const currentStep = ref(1)

const currentStepComponent = computed(() => components[currentStep.value - 1]);
const currentStepRef = ref(null);

const currentIndex = ref(0);

const payoutTurnMethods = ref([]);

const selectedPayoutMethod = ref(null);


const stepCaptions = [
    'Give the savings an identity',
    'Set the savings amount, duration & number of members',
    'Set the payment frequency, payout order method & penalty fee',
    'Set the start and end date of the savings',
    'Preview'
];


const nextStep = async () => {
    if (await currentStepRef.value.validate()) {
        if (currentStep.value < 5) {
        currentStep.value++;
        }
    }
};

const prevStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--;
  }
};

const setPayoutOption = (option) => {
    formData.payoutTurnMethod = option
}

//   watch(formData, () => {
//     v$.value.$reset()
//   }, 
//   { deep: true }
//   )


const updateForm = (newData) => {
  console.log(newData)
}


// states
const penaltyInfo = ref(false);
const startDateInfo = ref(false);
const endDateInfo = ref(false);
const penaltyCont = ref(null);
const startDateCont = ref(null);
const endDateCont = ref(null);
// const payoutTurnMethods = ref([]);
// const selectedPayoutMethod = ref(null);
const newPayoutMethod = ref(false);
const termsError = ref(false);
const isSubmitting = ref(false);


const paymentFreqOptions = ref([
    { value: 'Weekly', label: 'Weekly' },
    { value: 'Monthly', label: 'Monthly' },
])

const modelConfig = ref({
    type: "date", // Ensures only the date is selected
});


const showPenaltyInfo = () => {
    penaltyInfo.value = true;
}

const togglePenaltyInfo = () => {
    penaltyInfo.value =!penaltyInfo.value;
}

const config = {
    dateFormat: "d-m-Y", // Display date as YYYY-MM-DD
    minDate: "today",    // Disable past dates
    enableTime: false,   // Disable time selection
};

// Filters for disabling past dates
const disablePastDates = ref({
      date: (date) => {
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Reset time to midnight for comparison
        return date.getTime() <= today.getTime(); // Enable today and future dates
      },
});

const toggleStartDateInfo = () => {
    startDateInfo.value =!startDateInfo.value;
}

const toggleEndDateInfo = () => {
    endDateInfo.value =!endDateInfo.value;
}

const showStartDateInfo = () => {
    startDateInfo.value = true;
}

const showEndDateInfo = () => {
    endDateInfo.value = true;
}

const handleClickOutside = (event) => {
      // Check if the click happened outside the menu
      if (penaltyCont.value && !penaltyCont.value.contains(event.target)) {
            penaltyInfo.value = false; // Close the menu
      }
      else if (startDateCont.value && !startDateCont.value.contains(event.target)) {
            startDateInfo.value = false; // Close the menu
        }
      else if (endDateCont.value && !endDateCont.value.contains(event.target)) {
            endDateInfo.value = false; // Close the menu
      }
};

const payout_turns = () => {
    axios.get('/get_payment_turn_methods').then((res) => {
        payoutTurnMethods.value = res.data
        // console.log(res.data)
    })
}

// Computed property to get details of the selected option
const selectedPayoutMethodDetails = computed(() => {
  return payoutTurnMethods.value.find(
    (method) => method.id === form.payout_turn
  );
});

const selectPayoutTurnMethod = (res) => {
    console.log(res)
}

const handleSubmit = async() => {
    const isValid = await currentStepRef.value.validate();
    // console.log(isValid)
    
    if (!isValid) {
        return; // Stop if the form is not valid.
    }
    // v$.value.$touch(); // Mark all fields as touched to trigger validation messages
    // console.log(formData.checked)
    if(isValid){
    if(formData.checked){
        isSubmitting.value = true
        formData.post('/create_savings', {
            preserveScroll: true,
            onSuccess: () => {
                formData.reset();
                isSubmitting.value = false;
            },
            onError: (error) => {
                console.log(error)
                isSubmitting.value = false;
            }
        });
    }else{
        termsError.value = true;
    }
}
}

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
    payout_turns();
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});


</script>