<template>
    <Head title="Test " />

   <AuthLayout>
       <div class="w-[98%] md:w-1/2 mx-auto overflow-hidden space-y-3">
            <div class="w-full space-y-3 mt-5 bg-white border border-gray-200 shadow-lg rounded-lg p-4">
               <div class="text-lg text-gray-700 p-4 pb-0 space-x-4 space-y-5">
                    <div>
                        Click here to fire a private channel event
                    </div>
                    <div v-if="response" class="">
                        <div class="text-gray-700 text-lg mb-3">Here is the response of the event fired:</div>
                        <div class="text-gray-700">{{ response }}</div>
                    </div>
                    <div class="mt-5">
                        <button class="bg-primary rounded-full text-white px-6 py-3 text-center" @click="createEvent">Create Event</button>
                    </div>
                    <!-- <PrimaryButton> Create Another</PrimaryButton> -->
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup>
    import AuthLayout from '@/Layouts/AuthLayout2.vue';
    import { Head } from '@inertiajs/vue3';
    import { onMounted, ref } from 'vue';

    const props = defineProps({
        user: Object
    })

    const response = ref(null)

    const createEvent = () => {
        axios.post('/create_test_event_for_websocket', {}).then((res) => {
            console.log(res.data)
        })
    }

    onMounted(() => {
        window.Echo.private(`test.${props.user.id}`)
          .listen('TestEvent', (e) => {
            // console.log('Received event:', e)
            // notificationMessage.value = event.message;
            response.value = e
        });
    })
</script>