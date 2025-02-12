<template>
    <div class="w-full mx-auto p-5 space-y-4">
        <div class="p-4 w-full space-y-2">
            <div class="text-gray-600 text-md">1. Give the savings a unique title - This is the title that would identify this savings group </div>
            <TextField v-model="form.title" :rows="1"  placeholder = "Example: Matt savings group 2025" @blur="v$.title.$touch()" />
            <ErrorAlert :showError="v$.title.$error" :errorMessage="v$.title.$errors[0]?.$message" />
        </div>    
        <div class="p-4 w-full space-y-2">
            <div class="text-gray-600 text-md">2. Give a brief description of what the savings is about. Add any additional information you want to pass to members. </div>
            <TextField v-model="form.description" :rows="1"  @blur="v$.description.$touch()" />
            <ErrorAlert :showError="v$.description.$error" :errorMessage="v$.description.$errors[0]?.$message" />
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { required, helpers } from '@vuelidate/validators';
import TextField from '@/CustomComponents/TextField.vue';
import ErrorAlert from '@/CustomComponents/FormErrorAlert.vue'; 

const props = defineProps({
    form: Object
})

const emit = defineEmits(['update:modelValue']);

const localForm = ref({ ...props.form }); 

const rules = computed(() => ({
    title: {
        required: helpers.withMessage('The Title field is required', required), 
    },
    description: {
        required: helpers.withMessage('The Description field is required', required), 
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