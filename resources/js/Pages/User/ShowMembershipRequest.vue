<template>
     <Head title="Savings Request" />

    <AuthLayout>
        <div class="w-full md:w-1/2 mx-auto tracking-wide text-xl font-medium text-white px-2 py-2.5 bg-primary rounded-md mb-5 text-center"> Show membership request</div>
        <div class="w-[98%] md:w-1/2 mx-auto bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-4 w-full space-y-3">
                <div class="text-lg text-gray-700">Savings Membership Request</div>
                <div class="">Title - {{ membershipReq.savings?.title }}</div>
                <div>
                    <ul class="space-y-3">
                        <li><span class="text-gray-700">Name: </span><span><Link :href="route('profile.edit')">{{ membershipReq.user?.fullname }}</Link></span></li>
                        <li><span class="text-gray-700">Membership request made on: </span><span>{{ membershipReq.created }}</span></li>
                        <li><span class="text-gray-700">Phone No: </span><span>{{ membershipReq.user?.phone }}</span></li>
                    </ul>
                </div>
                <div class="w-full py-4 flex justify-center items-center space-x-4" v-if="membershipReq.status == 'pending'">
                    <RoundedBtn @click="acceptReq" :isSubmitting="isSubmitting">Accept</RoundedBtn>
                    <SecondaryRoundedBtn>Decline</SecondaryRoundedBtn>
                </div>
                <div v-else class="w-full p-4 bg-blue-100 text-blue-700 text-sm mt-4">
                    This request has been {{ membershipReq.status == 'approved' ? 'approved' : 'declined'}}.
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
        membershipReq: Object,
    })

    const isSubmitting = ref(false)

    const acceptReq = () =>{
        isSubmitting.value= true
        axios.post(`/approve-membership-request/${props.membershipReq.id}`)
        .then((res) => {
            isSubmitting.value = false
            Inertia.visit('/my-savings')
            // console.log(res.data)
        }).catch((err) => {
            console.log(err)
        })
    }

</script>