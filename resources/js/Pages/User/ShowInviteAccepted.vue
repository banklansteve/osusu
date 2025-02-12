<template>
    <Head title="Invite Accepted" />

   <AuthLayout>
       <div class="w-[98%] md:w-1/2 mx-auto bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">
           <div class="p-4 w-full space-y-3">
               <div class="text-lg text-primary font-semibold p-3 mt-2 text-center">Savings Membership Invite Accepted</div>
               <div class="px-2">
                    <div class="text-gray-700">
                        Your savings membership invite sent to {{ invite.user?.fullname }}  has been accepted.
                        <table class="table-auto mt-4 px-3">
                            <tbody>
                                <tr>
                                    <th class="py-2.5 text-left">Title:</th>
                                    <td class="pl-3">{{ saving.title }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2 text-left">Accepted On:</th>
                                    <td class="pl-3">{{ invite.accepted }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="w-full py-4 mt-2 flex justify-start items-center space-x-4" v-if="saving.status == 'pending'">
                        <Link :href="route('savings.show', {uid: saving.savings_uid})" class="transparent_rounded_btn smooth_transition">Go To Savings</Link>
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
       saving: Object,
       invite: Object,
   })
</script>