<template>
    <div class="relative">
      <button
        @click="toggleDropdown"
        class="w-full px-4 py-2.5 text-left bg-white border border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex justify-between items-center"
      >
        <span v-if="selectedOption">{{ selectedOption[labelField] }}</span>
        <span v-else class="text-gray-400">{{ placeholder }}</span>
        <span class="flex items-center">
          <svg
            :class="{ 'transform rotate-180': isOpen }"
            class="w-5 h-5 text-gray-400 transition-transform duration-200"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 20 20"
            fill="currentColor"
            aria-hidden="true"
          >
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </span>
      </button>
      <transition
        enter-active-class="transition duration-100 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-75 ease-in"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
      >
        <ul
          v-if="isOpen"
          class="absolute z-10 w-full py-1 mt-1 overflow-auto text-base bg-white rounded-md shadow-lg max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
        >
          <li
            v-for="option in options"
            :key="option[valueField]"
            @click="selectOption(option)"
            class="p-3 text-gray-800 cursor-pointer hover:bg-violet-100 transition-colors duration-150"
          >
            {{ option[labelField] }}
          </li>
        </ul>
      </transition>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, watch, defineEmits, onMounted, onUnmounted } from 'vue';
  
  const props = defineProps({
    options: {
      type: Array,
      required: true
    },
    modelValue: {
      type: [String, Number, Object],
      default: null
    },
    placeholder: {
      type: String,
      default: 'Select an option'
    },
    labelField: {
      type: String,
      default: 'label'
    },
    valueField: {
      type: String,
      default: 'value'
    }
  });
  
  const emit = defineEmits(['update:modelValue', 'option-selected']);
  
  const isOpen = ref(false);
  const selectedOption = ref(null);
  
  const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
  };
  
  const selectOption = (option) => {
    selectedOption.value = option;
    emit('update:modelValue', option[props.valueField]);
    emit("option-selected", option);
    isOpen.value = false;
  };
  
  watch(() => props.modelValue, (newValue) => {
    selectedOption.value = props.options.find(option => option[props.valueField] === newValue);
  }, { immediate: true });
  
  // Close dropdown when clicking outside
  const closeDropdown = (e) => {
    if (!e.target.closest('.relative')) {
      isOpen.value = false;
    }
  };
  
  onMounted(() => {
    document.addEventListener('click', closeDropdown);
  });
  
  onUnmounted(() => {
    document.removeEventListener('click', closeDropdown);
  });
  </script>
  