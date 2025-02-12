<template>
    <button :disabled="isSubmitting" class="bg-secondary text-white px-8 py-3 text-sm text-center rounded-full shadow-md tracking-wide focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-700 focus:ring-offset-2 transition ease-in-out duration-300 hover:bg-secondary_var">
        <slot />
    </button>
</template>

<script setup>
const props = defineProps({
    isSubmitting: Boolean
})
</script>