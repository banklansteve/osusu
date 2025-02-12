<template>
    <Head title="Created Savings" />

   <AuthLayout>
       <div class="w-[98%] md:w-1/2 mx-auto overflow-hidden space-y-3">
            <div v-if="$page.props.flash.message" class="relative bg-green-100 p-4 rounded-lg shadow-lg mb-3">
              <div class="text-sm text-green-800 tracking-wide">{{ $page.props.flash.message }}</div>
              <div class="absolute z-10 right-2 top-2">
                  <button @click="closeFlashMsg" class="bg-transparent rounded-full pa-3 hover:scale-110 transform transition-transform duration-300 focus:outline-none">
                    <CaCloseOutline class="text-red-700 text-2xl" />
                  </button>
              </div>
            </div>
            <div class="w-full space-y-3 mt-5 bg-white border border-gray-200 shadow-lg rounded-lg p-4">
               <div class="text-lg text-gray-700 p-4 pb-0 flex items-center space-x-4">
                    <span><button class="bg-transparent p-2" @click="goBack"><AkArrowLeft class="text-2xl text-gray-800" /></button></span>
                </div>
                <div class="text-lg font-semibold text-primary">Savings Group I belong to</div>
                <div v-if="savings?.length > 0" class="py-3 px-2 -mt-3">
                    <ul class="space-y-3 divide-y divide-gray-200">
                        <li v-for="saving in savings" :key="saving.id" class="p-3 hover:bg-violet-100 transition-all ease-in-out duration-300">
                            <Link :href="route('savings.show', {id: saving.savings_uid })">{{ saving.title }}</Link>
                        </li>
                    </ul>
                </div>
                <div v-else class="p-3 -mt-3">
                    <div class="text-gray-700">You have not joined any savings group</div>
                </div>
            </div>
            <div class="mt-8 bg-white border border-gray-200 shadow-lg rounded-lg pt-4 mx-auto overflow-hidden w-full">
                <div class="text-primary text-lg font-semibold p-4">Savings that i created</div>
                <div v-if="createdSavings?.length > 0" class="px-2">
                    <ul class="space-y-3 divide-y divide-gray-200 w-full pb-5">
                        <li v-for="saving in createdSavings" :key="saving.id" class="w-full p-3 hover:bg-violet-100 transition-all ease-in-out duration-300">
                            <Link :href="route('savings.show', {uid: saving.savings_uid})">{{ saving.title }}</Link>
                            <!-- {{ saving.savings_uid }} -->
                        </li>
                    </ul>
                </div>
                <div v-else class="pb-4 p-3">
                    <div class="text-gray-700 px-4 pb-4">You have not created any savings</div>
                </div>
            </div>
       </div>
   </AuthLayout>
</template>

<script setup>
   import { Head, Link } from '@inertiajs/vue3';
   import AuthLayout from '@/Layouts/AuthLayout2.vue';
   import { CaCloseOutline, AkArrowLeft } from '@kalimahapps/vue-icons';
   import { Inertia } from '@inertiajs/inertia';
 
   const props = defineProps({
       savings: Array,
       createdSavings: Array,
   })

   const goBack = () =>{
        Inertia.back();
   }
</script>