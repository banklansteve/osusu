<!-- components/Modal.vue -->
<template>
    <Teleport to="body">
      <Transition name="fade">
        <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-50 bg-black/50">
          <!-- Modal Content -->
          <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
            <!-- Close Button -->
            <button @click="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
              ✕
            </button>
  
            <!-- Modal Title -->
            <h2 class="text-lg font-semibold mb-4 text-center">Select a User</h2>
  
            <!-- Search Input -->
            <input 
              type="text"
              v-model="searchQuery"
              placeholder="Start typing a user name or email..."
              class="w-full px-3 py-2.5 border rounded-md focus:ring focus:ring-blue-300 bg-white"
            />
  
            <!-- Search Results Dropdown -->
            <div v-if="usersList.length" class="mt-4 border-gray-200 max-h-60 overflow-y-auto">
              <ul class="divide-y divide-gray-200">
                <li 
                  v-for="user in usersList"
                  :key="user.id"
                  @click="selectUser(user)"
                  class="p-3 cursor-pointer hover:bg-gray-100"
                >
                  {{ user.fullname }}
                </li>
              </ul>
            </div>
            <p v-else class="text-gray-500 text-sm mt-4">No users found.</p>
          </div>
        </div>
      </Transition>
    </Teleport>
  </template>
  
  <script setup>
  import { ref, computed, watch } from "vue";
  import axios from "axios";
  import { debounce } from "lodash"; // Import lodash debounce for delay handling
  
  const props = defineProps({
    isOpen: Boolean,       // Controls modal visibility
  });
  
  const emit = defineEmits(["update:isOpen", "userSelected"]);
  
  const searchQuery = ref(""); // Input field value
  const usersList = ref([]);   // List of users returned by the API
  
  // Fetch users from the API based on search query
  const fetchUsers = debounce(async () => {
    if (searchQuery.value.length > 2) { // Only search when query is longer than 2 chars
      try {
        const response = await axios.get('/api/search-users', {
          params: { query: searchQuery.value }
        });
        usersList.value = response.data.users; // Assuming the response contains 'users' array
      } catch (error) {
        console.error("Error fetching users:", error);
      }
    } else {
      usersList.value = [];
    }
  }, 500); // Wait for 500ms after the user stops typing
  
  // Watch search query and trigger the API call
  watch(searchQuery, () => {
    fetchUsers(); // Call the debounced fetch function
  });
  
  // Close modal function
  const closeModal = () => {
    emit("update:isOpen", false);
  };
  
  // Handle user selection
  const selectUser = (user) => {
    emit("userSelected", user); // Emit selected user to parent
    closeModal(); // Close modal
  };
  
  // Watch for modal open state and reset search
  watch(() => props.isOpen, (newVal) => {
    if (newVal) searchQuery.value = ""; // Reset input when opening modal
  });
  </script>
  
  <style scoped>
  /* Transition animations */
  .fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
  }
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
  }
  </style>
  