<template>
    <textarea
    ref="textareaRef"
    v-model="inputValue"
    :rows="rows"
    :placeholder="placeholder"
    class="w-full border border-gray-300 shadow-md bg-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-primary resize-none transition-all duration-300"
    :style="{ height: computedHeight }"
    @input="adjustHeight"
    @focus="$emit('focus')"
    @blur="$emit('blur')"
  ></textarea>
  </template>
  
  <script setup>
  import { ref, computed, defineEmits, watch } from "vue";
//   import defineEmit from "vueEmit
  
 const props = defineProps({
      modelValue: {
        type: String,
        default: "",
      },
      rows: {
        type: Number,
        default: 1,
      },
      placeholder: {
        type: String,
      },
      maxHeight: {
        type: String,
        default: "500px", // Maximum height for the textarea
        },
    })

    // states
    const inputValue = ref(props.modelValue);
    
    const textareaRef = ref(null);

    const emit = defineEmits(["update:modelValue", "focus", "blur"]);

    const adjustHeight = () => {
      if (textareaRef.value) {
        textareaRef.value.style.height = "auto"; // Reset height
        textareaRef.value.style.height = `${textareaRef.value.scrollHeight}px`; // Adjust height to fit content
        if (parseInt(textareaRef.value.style.height) > parseInt(props.maxHeight)) {
          textareaRef.value.style.height = props.maxHeight; // Cap height to maxHeight
          textareaRef.value.style.overflowY = "auto"; // Add scroll if maxHeight is reached
        } else {
          textareaRef.value.style.overflowY = "hidden";
        }
        computedHeight.value = textareaRef.value.style.height;
      }
      emit("update:modelValue", inputValue.value);
    };

    
      
    const computedHeight = ref("auto");

      // Watch for external updates to `modelValue`
    watch(
      () => props.modelValue,
      (newVal) => {
        inputValue.value = newVal;
        adjustHeight();
      }
    );
  </script>
  
  <style scoped>
  /* Primary color focus */
  .focus\:ring-primary:focus {
    --tw-ring-color: #4a90e2; /* Replace with your app's primary color */
  }
  </style>
  