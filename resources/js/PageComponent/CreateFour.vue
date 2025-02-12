<template>
    <div class="w-full mx-auto p-5 space-y-3">
        <div class="p-2 w-full spaxe-y-2">
            <div class="p-2 w-full mb-3">
                <div class="text-gray-600 text-md pb-2 w-full">9. When do you want the savings to start? Please choose a date.<span class="relative"><button class="bg-transparent cursor-pointer" @mouseenter="showStartDateInfo" @click="toggleStartDateInfo"><IoOutlineInformationCircle class="text-xl text-gray-blue-700" /></button>
                    <div v-if="startDateInfo" ref="startDateCont" class="absolute bottom-0 right-4 w-full md:min-w-[30rem] md:min-h-20 overflow-hidden bg-blue-100 text-blue-700 p-4 leading-6 tracking-normal text-sm border border-gray-200 rounded-lg shadow-md z-10">
                        This is flexible, as you can amend the start date later if, for example, you haven't met your members target
                    </div>
                </span>
                </div>
                <flat-pickr v-model="form.start_date" :config="config" class="shadow-lg border border-gray-200 rounded-lg" @blur="v$.start_date.$touch()" />
            </div>
            <ErrorAlert :showError="v$.start_date.$error" :errorMessage="v$.start_date.$errors[0]?.$message" />
        </div> 
        <div class="p-2 w-full spaxe-y-2">
            <div class="p-2 w-full mb-3">
                <div class="text-gray-600 text-md pb-2 w-full">10. When do you want the savings to end? Please choose a date.<span class="relative"><button class="bg-transparent cursor-pointer" @mouseenter="showEndDateInfo" @click="toggleEndDateInfo"><IoOutlineInformationCircle class="text-xl text-gray-blue-700" /></button>
                    <div v-if="endDateInfo" ref="endDateCont" class="absolute bottom-0 right-4 w-full md:min-w-[30rem] md:min-h-20 overflow-hidden bg-blue-100 text-blue-700 p-4 leading-6 tracking-normal text-sm border border-gray-200 rounded-lg shadow-md z-10">
                        This is flexible, as you can amend the start date later if, for example, you haven't met your members target
                    </div>
                </span>
                </div>
                <flat-pickr v-model="form.end_date" :config="config" class="shadow-lg border border-gray-200 rounded-lg" @blur="v$.end_date.$touch()" />
            </div>
            <ErrorAlert :showError="v$.end_date.$error" :errorMessage="v$.end_date.$errors[0]?.$message" />
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, helpers } from '@vuelidate/validators';
import { IoOutlineInformationCircle } from '@kalimahapps/vue-icons';
import ErrorAlert from '@/CustomComponents/FormErrorAlert.vue'; 
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';


const props = defineProps({
    form: Object
})

const emit = defineEmits(['update:modelValue']);

const localForm = ref({ ...props.form }); 

const startDateInfo = ref(false);
const endDateInfo = ref(false);
const startDateCont = ref(null);
const endDateCont = ref(null);

const config = {
    dateFormat: "d-m-Y", // Display date as YYYY-MM-DD
    minDate: "today",    // Disable past dates
    enableTime: false,   // Disable time selection
};

const toggleEndDateInfo = () => {
    endDateInfo.value =!endDateInfo.value;
}

const showStartDateInfo = () => {
    startDateInfo.value = true;
}

const showEndDateInfo = () => {
    endDateInfo.value = true;
}


const rules = computed(() => ({
    start_date: {
        required: helpers.withMessage('The Start date field is required', required), 
    },
    end_date: {
        required: helpers.withMessage('The End date field is required', required), 
    },
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
      if (startDateCont.value && !startDateCont.value.contains(event.target)) {
            startDateInfo.value = false; // Close the menu
        }
      else if (endDateCont.value && !endDateCont.value.contains(event.target)) {
            endDateInfo.value = false; // Close the menu
      }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});

</script>