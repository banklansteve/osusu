<template>
    <div class="relative w-full">
      <!-- Select Button -->
      <button 
        @click="toggleDropdown"
        class="w-full bg-white border border-gray-300 text-left p-3 rounded-md shadow-sm flex justify-between items-center focus:outline-none focus:ring focus:ring-blue-300"
      >
        <!-- Selected Value (or Placeholder) -->
        <span>{{ selectedOption ? selectedOption[labelField] : placeholder }}</span>
  
        <!-- Caret Icon -->
        <svg
          xmlns="http://www.w3.org/2000/svg"
          :class="{'rotate-180': isOpen, 'rotate-0': !isOpen}"
          class="h-4 w-4 transform transition-transform duration-200"
          fill="none" 
          viewBox="0 0 24 24" 
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
  
      <!-- Dropdown List -->
      <ul 
        v-if="isOpen" 
        class="absolute z-10 w-full mt-2 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-auto"
      >
        <li 
          v-for="(option, index) in options" 
          :key="index"
          @click="selectOption(option)"
          class="p-3 cursor-pointer hover:bg-blue-500 hover:text-white"
        >
          <slot name="option" :option="option">{{ option[labelField] }}</slot>
        </li>
      </ul>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';

  const props = defineProps({
    options: {
        type: Array,
        required: true
    },
    placeholder: {
      type: String,
      default: 'Select an option'
    },
    value: {
      type: [String, Number, Object],
      default: null
    },
    labelField: {
      type: String,
      default: 'label' // Default field to display in the dropdown
    },
    errorMessages:{
      type: Array
    }
})


  // states
  const isOpen = ref(false);
  const selectedOption = ref(props.value);


  // methods
  const toggleDropdown = () => {
      isOpen.value = !isOpen.value;
  };

  // Select option and close dropdown
  const selectOption = (option) => {
    selectedOption.value = option;
    isOpen.value = false;
    emit('update:selected', option); // Emit the selected option to the parent
  };

// watch: {
//     value(newVal) {
//       selectedOption.value = newVal; // Update selected option if value prop changes
//     }
// }
  
  // export default {
  //   props: {
  //     options: {
  //       type: Array,
  //       required: true
  //     },
  //     placeholder: {
  //       type: String,
  //       default: 'Select an option'
  //     },
  //     value: {
  //       type: [String, Number, Object],
  //       default: null
  //     },
  //     labelField: {
  //       type: String,
  //       default: 'label' // Default field to display in the dropdown
  //     },
  //     errorMessages:{
  //       type: Array
  //     } 
  //   },
  //   setup(props, { emit }) {
  //     const isOpen = ref(false);
  //     const selectedOption = ref(props.value);
  
  //     // Toggle dropdown visibility
  //     const toggleDropdown = () => {
  //       isOpen.value = !isOpen.value;
  //     };
  
  //     // Select option and close dropdown
  //     const selectOption = (option) => {
  //       selectedOption.value = option;
  //       isOpen.value = false;
  //       emit('update:selected', option); // Emit the selected option to the parent
  //     };
  
  //     return {
  //       isOpen,
  //       selectedOption,
  //       toggleDropdown,
  //       selectOption
  //     };
  //   },
  //   watch: {
  //     value(newVal) {
  //       selectedOption.value = newVal; // Update selected option if value prop changes
  //     }
  //   }
  // };
  </script>
  
  <style scoped>
  /* Caret rotate transition */
  .rotate-0 {
    transform: rotate(0deg);
  }
  
  .rotate-180 {
    transform: rotate(180deg);
  }
  </style>
  