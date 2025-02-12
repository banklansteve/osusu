<template>
    <Head title="Join" />

    <AuthLayout>
        <div class="py-4 w-full md:w-[50%] mx-auto p-3 flex justify-center">
            <div class="w-full pb-4 space-y-6 bg-white border border-gray-150 rounded-lg shadow-lg mx-auto">
                <div class="p-4 mt-5 text-gray-700 text-lg font-semibold text-center">{{ saving.title }}</div>
                <div class="">
                    <div class="text-primary font-semibold ml-4 px-4 leading-7 -mt-4">Description</div>
                    <div class="text-gray-700 ml-4 px-4">{{ saving.description }}</div>
                </div>
                <div class="my-2 ml-4">
                    <div class="font-semibold text-md text-primary px-4">Saving Detail</div>
                    <table class="table-auto ml-4">
                        <tbody>
                            <tr>
                                <th class="w-[40%] text-left py-3">Monthly Amount: </th>
                                <td class="pl-6">&pound;{{ saving.saving_amount }}</td>
                            </tr>
                            <tr>
                                <th class="w-[40%] text-left py-3">Total Members: </th>
                                <td class="pl-6">{{ saving.total_members }}</td>
                            </tr>
                            <tr>
                                <th class="w-[40%] text-left py-3">Savings Duration: </th>
                                <td class="pl-6">{{ saving.savings_duration }}</td>
                            </tr>
                            <tr>
                                <th class="w-[40%] text-left py-3">Total Payout: </th>
                                <td class="pl-6">&pound;{{ saving.total_payout }}</td>
                            </tr>
                            <tr>
                                <th class="w-[40%] text-left py-3">Payment Frequency: </th>
                                <td class="pl-6">{{ saving.payment_frequency }}</td>
                            </tr>
                            <tr>
                                <th class="w-[40%] text-left py-3">Payout Order Method: </th>
                                <td class="pl-6">{{ saving.payout_turn_method ?.name }}</td>
                            </tr>
                            <tr>
                                <th class="w-[40%] text-left py-3">Missed Payment Penalty: </th>
                                <td class="pl-6">&pound;{{ saving.penalty_rate }}</td>
                            </tr>
                            <tr>
                                <th class="w-[40%] text-left py-3">Start Date: </th>
                                <td class="pl-6">{{ saving.formatted_start_date }}</td>
                            </tr>
                            <tr>
                                <th class="w-[40%] text-left py-3">End Date: </th>
                                <td class="pl-6">{{ saving.formatted_end_date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mx-auto px-4 py-3 w-full">
                    <div class="bg-blue-100 text-blue-700 text-sm leading-7 p-4 overflow-hidden">
                        <div class="font-semibold">Please note</div>
                        <div>By requesting to join this savings group, you acknowledge and agree to abide by our terms and conditions. </div>
                        <div>Additionally, you accept the specific terms set by the savings group creator, including but not limited to penalties for missed payments. The savings creator reserves the right to decline your membership request at their discretion and may also terminate your membership at any time, with a full refund of your contributions.</div>
                    </div>
                </div>
                <div class="p-3 w-full md:w-[80%] space-y-3">
                    <div class="px-2 w-full grid gap-3 md:flex md:items-center md:space-x-2">
                        <label for="phoneNo">Please enter your phone Number</label>
                        <input type="text" v-model="form.phone" id="phoneNo" @blur="v$.phone.$touch()" class="w-full border border-gray-150 rounded-lg bg-white text-gray-700 px-3 py-2 focus focus:outline-none focus:ring-1 focus:ring-primary transition-all ease-in-out duration-300" />
                </div>
                <div class="px-2 w-full md:w-[70%] ml-auto">
                    <ErrorAlert :showError="v$.phone.$error" :errorMessage="v$.phone.$errors[0]?.$message" />
                </div>
                    <div class="mx-auto text-center mt-5">
                        <FlatSubmitBtn :isSubmitting="isSubmitting" @click="sendRequest">Send Request</FlatSubmitBtn>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup>
    import { ref, computed } from 'vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import AuthLayout from '@/Layouts/AuthLayout2.vue';
    import FlatSubmitBtn from '@/CustomComponents/FlatSubmitBtn.vue';
    import ErrorAlert from '@/CustomComponents/FormErrorAlert.vue'; 
    import { useVuelidate } from '@vuelidate/core';
    import { required, numeric, helpers } from '@vuelidate/validators';
    import { UiLoading } from '@kalimahapps/vue-icons';
    
    const props = defineProps({
        saving: Object
    })

    const form = useForm({
        phone: ''
    })

    const isSubmitting = ref(false)

    const rules = computed(() => ({
        phone: {
            required: helpers.withMessage('The phone number field is required', required), 
            numeric: helpers.withMessage('Only numeric values are allowed', numeric), 
        },
    }));

    const v$ = useVuelidate(rules, form);


    const sendRequest = () => {
        v$.value.$validate();

        if (!v$.value.$error) {
            isSubmitting.value = true
            form.post(`/post-membership-request/${props.saving.savings_uid}`, {
                onSuccess: () => {
                    form.reset()
                    isSubmitting.value = false;
                }
            })
            console.log(form.phone)
        }
    }
</script>