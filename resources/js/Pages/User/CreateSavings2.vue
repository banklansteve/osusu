<template>
    <Head title="Create Savings" />

    <AuthLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Savings</h2>
        </template>

        <div class="w-[98%] md:w-1/2 lg:w-1/3 mx-auto px-2 py-2.5 bg-primary text-white text-center rounded-md tracking-wide text-xl font-medium mb-3">Create New Saving</div>

        <div class="py-12 w-full md:w-3/4 lg:w-1/2 mx-auto min-h-screen">
            <div class="p-2 w-full mb-3">
                <div class="text-gray-600 text-md pb-2">1. Give the savings a unique title - This is the title that would identify this savings group </div>
                <TextField v-model="form.title" :rows="1"  placeholder = "Example: Matt savings group 2025" />
            </div>
            <div class="p-2 w-full mb-3">
                <div class="text-gray-600 text-md pb-2">2. Give a brief description of what the savings is about. Add any additional information you want to pass to members. </div>
                <TextField v-model="form.description" :rows="1" placeholder="" />
            </div>
            <div class="p-2 w-full mb-3">
                <div class="text-gray-600 text-md pb-2">3. How much is each member contributing?</div>
                <TextField v-model="form.saving_amount" :rows="1" placeholder="Example: 100" />
            </div>
            <div class="p-2 w-full mb-3">
                <div class="text-gray-600 text-md pb-2">4. What is the savings duration (in months)</div>
                <TextField v-model="form.savings_duration" :rows="1" placeholder="Example: 10" />
            </div>
            <div class="p-2 w-full mb-3">
                <div class="text-gray-600 text-md pb-2">5. How many members will be part of this savings? This should normally be equal to the savings duration</div>
                <TextField v-model="form.total_members" :rows="1" placeholder="Example: 10" />
            </div>
            <div class="p-2 w-full mb-3">
                <div class="text-gray-600 text-md pb-2">6. What will be the payment frequency of this savings?</div>
                <Select v-model="form.payment_frequency" :options="paymentFreqOptions"  placeholder="Select Payment Frequency" labelField="label" value="value">
                    <template #option="{ option }">
                        <div class="flex items-center">
                            <span class="font-semibold">{{ option.label }}</span>
                        </div>
                    </template>
                </Select>
            </div>
            <div class="p-2 w-full mb-3">
                <div class="text-gray-600 text-md pb-2 w-full">7. How do you want the payout turns decided? </div>
                <Select v-model="form.payout_turn" :options="payoutTurnMethods"  placeholder="Select Payout Turns Method" labelField="name" valueField="id" @update:modelValue="selectPayoutTurnMethod">
                    <template #option="{ option }">
                        <div class="flex items-center">
                            <span class="font-semibold">{{ option.name }}</span>
                        </div>
                    </template>
                </Select>
                
                <div v-if="selectedPayoutMethodDetails" class="my-3 w-full bg-blue-100 text-blue-700 rounded-md text-sm p-4 leading-6 space-y-2">
                    <!-- <span class="text-2xl flex items-center justify-center text-center"><GlInformationO /></span> -->
                    <div><span class="font-semibold tracking-wide">Description: </span> <span>{{ selectedPayoutMethodDetails.description }}</span> </div>
                    <div><span class="font-semibold tracking-wide">Benefits: </span> <span>{{ selectedPayoutMethodDetails.benefits }}</span> </div>
                 </div>
            </div>
            <div class="p-2 w-full mb-3">
                <div class="text-gray-600 text-md pb-2 w-full">8. When a member misses payment by more than 24 hours, how much penalty do you want them to pay? <span class="relative"><button class="bg-transparent cursor-pointer" @mouseenter="showPenaltyInfo" @click="togglePenaltyInfo"><IoOutlineInformationCircle class="text-xl text-gray-blue-700" /></button>
                    <div v-if="penaltyInfo" ref="penaltyCont" class="absolute bottom-0 right-4 w-full md:min-w-[30rem] md:min-h-20 overflow-hidden bg-blue-100 text-blue-700 p-4 leading-6 tracking-normal text-sm border border-gray-200 rounded-lg shadow-md z-10">
                        This is entirely up to the group creator to set. If you do not want to set a penalty fee, then leave the field blank. See details about penalty fee in our <Link :href="route('faq')" class="text-blue-800 font-semibold underline">FAQ page </Link>
                </div>
                </span></div>
                <TextField v-model="form.penalty_rate" :rows="1" placeholder="Example: 10" />
            </div>
            <div class="p-2 w-full mb-3">
                <div class="text-gray-600 text-md pb-2 w-full">9. When do you want the savings to start? Please choose a date.<span class="relative"><button class="bg-transparent cursor-pointer" @mouseenter="showStartDateInfo" @click="toggleStartDateInfo"><IoOutlineInformationCircle class="text-xl text-gray-blue-700" /></button>
                    <div v-if="startDateInfo" ref="startDateCont" class="absolute bottom-0 right-4 w-full md:min-w-[30rem] md:min-h-20 overflow-hidden bg-blue-100 text-blue-700 p-4 leading-6 tracking-normal text-sm border border-gray-200 rounded-lg shadow-md z-10">
                        This is flexible, as you can amend the start date later if, for example, you haven't met your members target
                    </div>
                </span>
                </div>
                <flat-pickr v-model="form.start_date" :config="config" class="shadow-lg border border-gray-200 rounded-lg" />
            </div>
            <div class="p-2 w-full mb-3">
                <div class="text-gray-600 text-md pb-2 w-full">10. When do you want the savings to end? Please choose a date.<span class="relative"><button class="bg-transparent cursor-pointer" @mouseenter="showEndDateInfo" @click="toggleEndDateInfo"><IoOutlineInformationCircle class="text-xl text-gray-blue-700" /></button>
                    <div v-if="endDateInfo" ref="endDateCont" class="absolute bottom-0 right-4 w-full md:min-w-[30rem] md:min-h-20 overflow-hidden bg-blue-100 text-blue-700 p-4 leading-6 tracking-normal text-sm border border-gray-200 rounded-lg shadow-md z-10">
                        This is flexible, as you can amend the start date later if, for example, you haven't met your members target
                    </div>
                </span>
                </div>
                <flat-pickr v-model="form.end_date" :config="config" class="shadow-lg border border-gray-200 rounded-lg" />
            </div>
            <div class="p-2 w-full mb-3">
                <CustomCheckBox v-model="form.checked" label="I agree to the Terms and Conditions" />
            </div>
            <div v-if="termsError" class="flex items-center space-x-5 p-3 mb-2 w-full bg-red-200 text-red-700 text-sm rounded-md" >
                <span><BxSolidError class="text-xl" /></span> 
                <span>Please accept our terms and conditions</span>
            </div>
            <div class="p-2 w-full mb-3">
                <Button @click="handleSubmit" :isSubmitting="isSubmitting" class="flex items-center"><span>Create Savings</span><span v-if="isSubmitting" class="ml-2"><UiLoading class="animate-spin text-2xl" /></span></Button>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout2.vue';
import TextField from '@/CustomComponents/TextField.vue';
import Select from '@/CustomComponents/CustomSelects.vue';
import CustomCheckBox from '@/CustomComponents/CustomCheckBox.vue';
import Button from '@/CustomComponents/FlatSubmitBtn.vue'
import { IoOutlineInformationCircle, BxSolidError, UiLoading } from '@kalimahapps/vue-icons';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';


const form = useForm({
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
});


// states
const penaltyInfo = ref(false);
const startDateInfo = ref(false);
const endDateInfo = ref(false);
const penaltyCont = ref(null);
const startDateCont = ref(null);
const endDateCont = ref(null);
const payoutTurnMethods = ref([]);
const selectedPayoutMethod = ref(null);
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

const handleSubmit = () => {
    if(termsError.value == true){
        isSubmitting.value = true
        // form.post('/create_savings', {
        //     preserveScroll: true,
        //     onSuccess: () => {
        //         form.reset();
        //         isSubmitting.value = false;
        //     }
        // });
    }else{
        termsError.value = true;
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