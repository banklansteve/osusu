
<template>
    <Head title="Dashboard" />

    <AuthLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12 w-full">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full">
                <div class="h-screen flex justify-center mx-auto w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mx-auto w-full">
                        <div class="bg-secondary rounded-lg shadow-lg p-3 border border-secondary h-64 overflow-hidden text-center hover:scale-105 hover:shadow-lg transition-transform duration-300 ease-in-out">
                            <div class="text-2xl flex justify-center items-center space-x-3 p-2"><HeOutlineMoneyBag class="text-[3rem] text-white" /><span class="text-2xl text-white">Savings</span></div>
                        </div>
                        <div class="bg-secondary_var rounded-lg shadow-lg p-3 border border-secondary h-64 overflow-hidden text-center hover:scale-105 hover:shadow-lg transition-transform duration-300 ease-in-out">
                            <div class="text-2xl flex justify-center items-center space-x-3 p-2"><FaRegCircleUser class="text-[3rem] text-white" /><span class="text-2xl text-white">Profile</span></div>
                        </div>
                        <div class="bg-secondary_var2 rounded-lg shadow-lg p-3 border border-secondary h-64 overflow-hidden text-center hover:scale-105 hover:shadow-lg transition-transform duration-300 ease-in-out">
                            <div class="text-2xl flex justify-center items-center space-x-3 p-2"><HeOutlineMoneyBag class="text-[3rem] text-white" /><span class="text-2xl text-white">Savings</span></div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AuthLayout from '@/Layouts/AuthLayout2.vue';
import { Head } from '@inertiajs/vue3';
import { HeOutlineMoneyBag, FaRegCircleUser } from '@kalimahapps/vue-icons';
</script>

