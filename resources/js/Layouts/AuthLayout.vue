<template>
    <div class="">
    <header class="fixed top-0 left-0 w-full bg-white shadow z-50">
      <nav class="flex items-center justify-between px-6 py-4 md:px-12">
        <!-- Mobile Menu Button -->
        <button 
          class="block md:hidden focus:outline-none" 
          @click="toggleMenu"
        >
            <span><ChMenuHamburger class="text-primary text-3xl"/></span>
        </button>
  
        <!-- Logo (always centered on mobile) -->
        <div class="absolute left-1/2 transform -translate-x-1/2 md:relative md:left-0 md:transform-none">
            <div class="text-primary text-3xl font-semibold font-mono">Osusu</div>
        </div>
  
        <!-- Navigation Links -->
        <div 
          class="fixed inset-0 bg-white transform -translate-x-full transition-all duration-500 ease-in-out md:relative md:translate-x-0 md:flex md:items-center md:space-x-6 md:border-none"
          :class="{'translate-x-0': isMenuOpen, 'border-r border-gray-300': isMenuOpen, 'w-1/2': isMenuOpen}">
          <!-- Close Button (Mobile) -->
          <button class="absolute top-4 right-4 block md:hidden focus:outline-none"  @click="toggleMenu">
            <ClCloseSm class="text-primary text-3xl" />
          </button>
  
          <!-- Links (stacked vertically on mobile, aligned left) -->
          <div class="mt-16 flex flex-col items-start space-y-6 pl-6 md:mt-0 md:space-y-0 md:flex-row md:space-x-6">
            <template v-if="user">
              <span class="text-sm font-semibold text-gray-700">Hello, {{ user.first_name }}</span>
              <button class="text-sm font-semibold text-gray-700 hover:underline" @click="goToProfile">
                Profile
              </button>
              <Link :href="route('logout')" method="post" as="button" class="text-primary border px-2.5 border-transparent rounded-2xl hover:text-red-700 hover:border-red-700 focus:ring-offset-2 transition ease-in-out duration-300">Logout</Link>
            </template>
            <template v-else>
              <button class="text-sm font-semibold text-gray-700 hover:underline" @click="goToLogin">
                Login
              </button>
              <button class="text-sm font-semibold text-gray-700 hover:underline" @click="goToRegister">
                Register
              </button>
            </template>
          </div>
        </div>
      </nav>
    </header>
    <main class="mt-20 mx-auto w-screen">
      <slot></slot>
    </main>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { usePage, Link } from '@inertiajs/vue3';
  import { ChMenuHamburger, ClCloseSm } from '@kalimahapps/vue-icons';
  
  const isMenuOpen = ref(false);
  
  const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
  };
  
  // Get current user (from Inertia shared data)
  const { props } = usePage();
  const user = usePage().props.auth.user;
  
  const goToLogin = () => {
    $inertia.get('/login');
  };
  
  const goToRegister = () => {
    $inertia.get('/register');
  };
  
  const goToProfile = () => {
    $inertia.get('/profile');
  };
  </script>
  
  <style scoped>
  /* Add custom styles if needed */
  </style>
  