<template>
    <div v-if="showError" class="mt-n2 flex items-center space-x-4 p-2.5 mb-4 text-red-800 bg-red-100 rounded-lg" role="alert">
        <span><BxSolidError class="text-red text-xl"/></span>
        <span class="text-sm">{{ errorMessage }}</span>
    </div>
</template>


<script setup>
import { BxSolidError } from '@kalimahapps/vue-icons';

// Accept props for the error condition and error message
const props = defineProps({
    showError: {
        type: Boolean,
        required: true,
        default: false,
    },
    errorMessage: {
        type: String,
        default: '',
    },
});
</script>