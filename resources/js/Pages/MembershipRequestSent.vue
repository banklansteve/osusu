<template>
    <Head title="Join" />

    <AuthLayout>
        <div class="py-4 w-full md:w-[50%] mx-auto p-3 flex justify-center">
            <div class="w-full pb-4 space-y-3 bg-green-200 rounded-lg shadow-lg mx-auto">
                <div class="mt-6 text-green-700 text-lg font-semibold text-center">Request Sent!</div>
                <div class="px-5 text-green-700 leading-7">
                    Your request to join the savings group has been submitted successfully. 
                    A confirmation email has been sent to you. 
                    You will also be notified via email once the group admin reviews and responds to your request. Thank you.
                </div>
                <div class="text-center p-3">
                    <Link :href="route('home')" class="bg-secondary px-4 py-2.5 text-white text-sm text-center rounded-full focus:shadow-md tracking-wide focus:bg-secondary active:bg-secondary focus:outline-none focus:ring-2 focus:ring-indigo-700 focus:ring-offset-2 transition ease-in-out duration-300 hover:bg-secondary_var">
                            Go Back To Home
                    </Link>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>


<script setup>
    import { Head, Link } from '@inertiajs/vue3';
    import AuthLayout from '@/Layouts/AuthLayout2.vue';
</script>