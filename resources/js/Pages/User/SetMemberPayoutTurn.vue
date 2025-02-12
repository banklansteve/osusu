<template>
    <Head title="Set Payout Turn" />

   <AuthLayout>
       <div class="w-[98%] md:w-1/2 mx-auto bg-white rounded-lg shadow-lg border border-gray-100">
           <div class="p-4 w-full space-y-3">
               <div class="text-lg text-primary font-semibold p-3 mt-2 text-start">Set Payout Turn For {{ saving.title }}</div>
                <div class="w-full px-3">
                    <table class="table-auto">
                        <tbody>
                            <tr>
                                <th class="py-3 text-left">Name:</th>
                                <td class="text-left pl-3">{{ user.fullname }}</td>
                            </tr>
                            <tr>
                                <th class="py-3 text-left">Saving Title:</th>
                                <td class="text-left pl-3">{{ saving.title }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="w-full flex items-center space-x-5 text-gray-700 mt-2">
                        <div class="whitespace-nowrap flex-shrink-0">Choose {{ user.fullname }}'s turn</div>
                        <div class="px-2 w-full">
                            <CustomSelect v-model="selectedTurn" :options="formattedTurns"  placeholder="Select Payout Turn" value="value" @option-selected="handleTurnSelect">
                                <template #option="{ option }">
                                    <div class="flex items-center">
                                        <span class="font-semibold">Turn {{ option.label }}</span>
                                    </div>
                                </template>
                            </CustomSelect>
                        </div>
                    </div>
                    <div v-if="selectedTurn" class="my-3 p-3">
                        <button @click="assignTurn" :disabled="isSubmitting" class="bg-primary text-center text-sm rounded-lg text-white py-2.5 px-5 hover:bg-primary_var smooth_transition">Choose Turn</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout2.vue';
import CustomSelect from '@/CustomComponents/CustomSelects.vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    saving: Object,
    user: Object,
    availableTurns: Array,
})

const selectedTurn = ref(null)

const isSubmitting = ref(false)


const formattedTurns = computed(() => {
    return props.availableTurns.map(turn => ({
        label: `Turn ${turn}`,  // The display label for the turn
        value: turn   // The actual value (used for the backend)
    }));
});

const handleTurnSelect = (option) => {
    // selectedTurn.value = option.
    console.log(option)
}

const assignTurn = () => {
    isSubmitting.value = true
    axios.post(`/api/assign-payout-turn/${props.saving.id}/${props.user.id}`, {
        turn: selectedTurn.value
    }).then((res) => {
            isSubmitting.value = false
            if(res.data.message = 'Turn assigned'){
                Inertia.visit(`/saving/${props.saving.savings_uid}`);
            }
            console.log(res.data)
        })
}
</script>
              