<template>
    <div>
      <div v-for="(option, index) in options" :key="index" class="flex items-center space-x-3 my-2">
        <input type="radio" :id="`${name}-${index}-${uid}`" :name="name" :value="option[valueField]" :checked="modelValue === option[valueField]" @change="updateValue(option[valueField])" class="hidden peer" />
        <!-- <input type="radio" :id="`${name}-${index}-${uid}`" :name="name" :value="option[valueField]" v-model="localValue" class="hidden peer" /> -->
        <div @click="selectOption(option[valueField])"
          :class="[
            'w-6 h-6 border-2 rounded-full flex items-center justify-center cursor-pointer transition-all duration-300 ease-in-out',
            localValue === option[valueField] ? 'bg-primary border-primary' : 'bg-white border-gray-300',
            ]">
          <div v-if="localValue === option[valueField]" class="w-3 h-3 bg-white rounded-full"></div>
        </div>
        <label :for="`${name}-${index}-${uid}`" @click="selectOption(option[valueField])" class="cursor-pointer text-gray-700">
          <slot name="label" :option="option">{{ option[labelField] }}</slot>
        </label>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits, ref, watch } from 'vue'
//   import { watch } from 'vue';
  
  // Define props for the component
  const props = defineProps({
    modelValue: [String, Number], // Model value for v-model binding
    options: {
      type: Array,
      required: true // Array of options for the radio buttons
    },
    name: {
      type: String,
      required: true // Unique name for each radio group to avoid conflicts
    },
    labelField: {
      type: String,
      required: true // Field name to display for each option label
    },
    valueField: {
      type: String,
      required: true // Field name to bind as the radio value
    }
  })
  
  // Define emit to handle v-model updates
    const emit = defineEmits(['update:modelValue'])
  
  // Create a local ref to bind to the input element
    const localValue = ref(props.modelValue)
    const uid = ref(Math.random().toString(36).substr(2, 9)) // Generate a unique id

    // Watch the parent-provided modelValue and sync with localValue
    watch(() => props.modelValue, (newValue) => {
        localValue.value = newValue
    })

    // Emit the value when the user selects a radio button
    const selectOption = (value) => {
        localValue.value = value
        emit('update:modelValue', value) // Emit the selected value to the parent
    }
  </script>
 