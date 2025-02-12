<template>
    <Head title="Join" />

    <AuthLayout>
        <div class="py-4 w-full md:w-[80%] mx-auto p-3 flex justify-center">
            <div class="grid w-full gap-4 md:grid-cols-3">
                <div class="md:col-span-2 w-full"> 
                    <div class="p-4 w-full pb-4 space-y-6 bg-white border border-gray-150 rounded-lg shadow-lg">
                        <div class="w-full py-3 flex items-start space-x-4 mb-5">
                            <template v-if="saving.creator && saving.creator.profile_pic">
                                <img :src="saving.creator.profile_pic" alt="Avatar" class="w-12 h-12 rounded-full object-cover" />
                            </template>
                            <template v-else>
                                <div class="rounded-full overflow-hidden w-10 h-10">
                                    <div class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-primary rounded-full dark:bg-gray-600">
                                        <span class="font-medium text-sm text-white dark:text-gray-300">{{ saving.creator && saving.creator.initials }}</span>
                                    </div>
                                </div>
                            </template>
                            <div>
                                <p class="text-base text-gray-600">{{ saving.creator && saving.creator.fullname }}</p>
                                <p class="text-gray-400 text-xs">{{ saving.created }}</p>
                            </div>
                        </div>
                        <div class="ml-4 mt-5 text-xl text-gray-600 font-semibold">{{ saving.title }}</div>
                        <div class="ml-4">
                            <div class="text text-lg text-gray-700 font-semibold">Description</div>
                            <div class="text text-lg text-gray-700">{{ saving.description }}</div>
                        </div>
                        <div class="m-3 w-full">
                            <Link :href="route('join.request', {uid: saving.savings_uid})" class="bg-primary px-4 py-2 rounded-lg text-white border border-transparent text-sm text-center hover:bg-transparent hover:text-primary hover:border hover:border-primary transition-all ease-in-out duration-300">Request To Join</Link>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 shadow rounded-lg"> 
                    <div class="text-lg tracking-wide my-3 font-semibold text-primary py-2 px-3">Saving Detail</div>
                    <div class="text-gray-600 px-3">
                        <table class="table-auto">
                            <tbody>
                                <tr class="py-3">
                                    <th class="w-[50%] text-left py-2">Creator: </th>
                                    <td>{{ saving.creator && saving.creator.fullname }}</td>
                                </tr>
                                <tr>
                                    <th class="w-1/2 text-left py-2 space-x-2">Target Members: </th>
                                    <td>{{ saving.total_members }}</td>
                                </tr>
                                <tr>
                                    <th class="w-1/2 text-left py-2 space-x-3">Current Members: </th>
                                    <td>{{ saving.members ? saving.members.length : 0 }}</td>
                                </tr>
                                <tr>
                                    <th class="w-1/2 text-left py-2 space-x-3">Saving Amount: </th>
                                    <td>&pound;{{ saving.saving_amount }}</td>
                                </tr>
                                <tr>
                                    <th class="w-1/2 text-left py-2 space-x-3">Saving Frequency: </th>
                                    <td class="">{{ saving.payment_frequency }}</td>
                                </tr>
                                <tr>
                                    <th class="w-1/2 text-left py-2 space-x-3">Saving Duration: </th>
                                    <td class="">{{ saving.savings_duration }}</td>
                                </tr>
                                <tr>
                                    <th class="w-1/2 text-left py-2 space-x-3">Penalty Rate: </th>
                                    <td class="">&pound;{{ saving.penalty_rate }}</td>
                                </tr>
                                <tr>
                                    <th class="w-1/2 text-left py-2 space-x-3">Payout Order Method: </th>
                                    <td class="">{{ saving.payout_turn_method && saving.payout_turn_method.name }}</td>
                                </tr>
                                <tr>
                                    <th class="w-1/2 text-left py-2 space-x-3">Start Date: </th>
                                    <td class="">{{ saving.formatted_start_date }}</td>
                                </tr>
                                <tr>
                                    <th class="w-1/2 text-left py-2 space-x-3">Penalty Rate: </th>
                                    <td class="">{{ saving.formatted_end_date }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'; 
import AuthLayout from '@/Layouts/AuthLayout2.vue';

const props = defineProps({
    saving: Object
})
</script>