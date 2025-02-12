<template>
    <Head title="FAQ" />

   <AuthLayout>
       <template #header>
           <h2 class="font-semibold text-xl text-gray-800 leading-tight">FAQ</h2>
       </template>

       <div class="py-12 w-full md:w-3/4 lg:w-1/2 mx-auto min-h-screen">
          This is the terms and conditions page
       </div>
   </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout2.vue';
</script>