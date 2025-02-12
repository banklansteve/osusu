<template>
    <label class="flex items-center cursor-pointer">
      <input
        type="checkbox"
        class="hidden peer"
        :checked="modelValue"
        @change="$emit('update:modelValue', $event.target.checked)"
      />
      <div
        class="w-6 h-6 flex justify-center items-center bg-white border-2 border-gray-300 rounded-md transition-all peer-checked:bg-primary peer-checked:border-primary peer-focus:ring-2 peer-focus:ring-primary"
      >
        <svg
          v-if="modelValue"
          xmlns="http://www.w3.org/2000/svg"
          class="w-4 h-4 text-white"
          viewBox="0 0 20 20"
          fill="currentColor"
        >
          <path
            fill-rule="evenodd"
            d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z"
            clip-rule="evenodd"
          />
        </svg>
      </div>
      <span v-if="label" class="ml-2 text-gray-800">{{ label }}</span>
    </label>
  </template>
  
  <script setup>
  const props = defineProps({
    modelValue: {
      type: Boolean,
      default: false,
    },
    label: {
      type: String,
      default: '',
    },
  });
  
  const emit = defineEmits(['update:modelValue']);
  </script>
  
  