<template>
    <Head title="Savings Invite" />

   <AuthLayout>
       <div class="w-[98%] md:w-1/2 mx-auto bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">
           <div class="p-4 w-full space-y-3">
               <div class="text-lg text-primary font-semibold p-3 mt-2 text-center">Savings Membership Invite</div>
               <div class="px-2" v-if="invite">
                    <div class="text-gray-700">
                        You have a savings membership invite with details as follows:
                        <table class="table-auto mt-4">
                            <tbody>
                                <tr>
                                    <th class="py-2.5 text-left">Title:</th>
                                    <td>{{ saving.title }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2.5 text-left">Invited By:</th>
                                    <td>{{ saving.creator?.fullname }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2.5 text-left">Saving Amount:</th>
                                    <td>&pound;{{ saving.saving_amount }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2.5 text-left">Total Payout:</th>
                                    <td>&pound;{{ saving.total_payout }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2.5 text-left">Payment Frequency:</th>
                                    <td>{{ saving.payment_frequency }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2.5 text-left">Savings Duration:</th>
                                    <td>{{ saving.savings_duration }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2 text-left">Total Members:</th>
                                    <td>{{ saving.total_members }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2 text-left">Penalty Rate:</th>
                                    <td>&pound;{{ saving.penalty_rate }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2 text-left">Start Date:</th>
                                    <td>{{ saving.formatted_start_date }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2 text-left">End Date:</th>
                                    <td>{{ saving.formatted_end_date }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="w-full py-4 mt-2 flex justify-start items-center space-x-4" v-if="saving.status == 'pending'">
                        <RoundedBtn @click="acceptReq" :isSubmitting="isSubmitting">Accept</RoundedBtn>
                        <SecondaryRoundedBtn @click="declineReq">Decline</SecondaryRoundedBtn>
                    </div>
                    <div v-if="saving.status == 'accepted'" class="p-3 px-3 rounded-md bg-blue-100 text-blue-700 text-sm">
                        You have already accepted this membership invite.
                    </div>
               </div>
               <div v-else>
                    <div class="text-red-700 bg-red-100 p-3">This invite does not exist. </div>
               </div>
           </div>
       </div>
   </AuthLayout>
</template>

<script setup>
   import { ref } from 'vue';
   import { Head, useForm, Link } from '@inertiajs/vue3';
   import AuthLayout from '@/Layouts/AuthLayout2.vue';
   import RoundedBtn from '@/CustomComponents/RoundedBtn.vue';
   import SecondaryRoundedBtn from '@/CustomComponents/SecondaryRoundedBtn.vue';
import { Inertia } from '@inertiajs/inertia';

   const props = defineProps({
       saving: Object,
       invite: Object,
   })

   const isSubmitting = ref(false)

   const acceptReq = () =>{
       isSubmitting.value= true
       axios.post(`/api/accept-membership-invite/${props.saving.id}/${props.invite.invite_token}`)
       .then((res) => {
           isSubmitting.value = false
           if(res.data.message = 'Member accepted'){
               Inertia.visit('/my-savings')
           }
           // console.log(res.data)
       }).catch((err) => {
           console.log(err)
       })
   }

const declineReq = () => {

}

</script>