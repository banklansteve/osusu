<template>
    <Head title="Savings" />

    <AuthLayout :saving="saving.id">
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Savings</h2>
        </template> -->

        <div class="py-4 w-full md:w-[80%] mx-auto p-3 flex justify-center">
            <div v-if="$page.props.flash.message" class="relative bg-green-100 p-4 rounded-lg shadow-lg mb-3">
              <div class="text-sm text-green-800 tracking-wide">{{ $page.props.flash.message }}</div>
              <div class="absolute z-10 right-2 top-2">
                  <button @click="closeFlashMsg" class="bg-transparent rounded-full pa-3 hover:scale-110 transform transition-transform duration-300 focus:outline-none">
                    <CaCloseOutline class="text-red-700 text-2xl" />
                  </button>
              </div>
            </div>
            <div class="grid w-full gap-4 md:grid-cols-3">
                <div class="md:col-span-2 w-full"> 
                    <div class="p-4 w-full pb-4 space-y-6 bg-white border border-gray-150 rounded-lg shadow-lg">
                        <div class="w-full py-3 flex items-start space-x-4">
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
                        <div class="ml-4">
                            <div class="text text-lg text-gray-700 font-semibold">Description</div>
                            <div class="text text-lg text-gray-700">{{ saving.description }}</div>
                        </div>
                        <div class="w-full p-3 flex items-center space-x-6" v-if="isCreator">
                            <div class="" v-if="saving.status='pending'">
                                <button class="bg-transparent px-3 py-2 text-primary flex space-x-1 items-center rounded-full hover:text-white hover:bg-primary transition-all ease-in-out duration-300">
                                    <span><AnOutlinedEdit class="text-xl" /></span>
                                </button>
                            </div>
                            <div class="" v-if="saving.status='pending'">
                                <button class="bg-transparent px-3 py-2 text-red-700 flex space-x-1 items-center rounded-full hover:text-white hover:bg-red-700 transition-all ease-in-out duration-300">
                                    <span><FlDelete class="text-xl" /></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 w-full pb-4 space-y-6 bg-white border border-gray-150 rounded-lg shadow-lg mt-4">
                        <div class="w-full flex justify-between items-center">
                            <div class="text-lg tracking-wide pt-3 font-semibold text-primary px-3">Members <span class="chip" v-if="saving.members?.length > 0">{{ saving.members.length }}</span></div>
                            <div class="flex items-center space-x-3" v-if="isCreator">
                                <button @click="addSelfToGroup" class="rounded-lg px-5 py-2.5 text-primary bg-transparent text-center hover:bg-violet-200 hover:shadow-md transition-all ease-in-out duration-300" v-if="!isMember">Become A Member</button>
                                <button @click="UserSearchModalOpen = true" class="rounded-lg px-5 py-2.5 text-primary bg-transparent text-center hover:bg-violet-200 hover:shadow-md transition-all ease-in-out duration-300" v-if="saving.members.length < saving.total_members">Add Member</button>
                            </div>
                        </div>
                        <template v-if="isSendingInvite">
                            <template v-if="inviteError">
                                <div class="w-full p-3 bg-red-100 text-red-700 text-sm rounded-md shadow-md flex items-center space-x-5">
                                    <span><GlError class="text-lg"/></span>
                                    <span>{{ inviteErrorMsg }}</span>
                                </div>
                            </template>
                            <template v-else>
                                <div class="w-full p-3 pb-8 flex items-center space-x-6 border-b border-gray-150">
                                    <div class="text-gray-700 whitespace-nowrap flex-shrink-0">{{ userToInvite ?.fullname }}</div>
                                    <div class="flex w-full items-center space-x-5">
                                        <button @click="sendInvite" :disabled="isSubmitting" class="bg-secondary px-4 py-2 text-center text-sm rounded-lg text-white">Send Invite</button>
                                        <button @click="cancelInvite" class="bg-red-700 px-4 py-2 text-center text-sm rounded-lg text-white">Cancel </button>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <div class="text-gray-600 w-full">
                            <div class="w-full" v-if="members.length > 0">
                                <table class="table-auto w-full">
                                    <thead>
                                        <tr>
                                            <th class="text-left">S/N</th>
                                            <th class="text-left">Name</th>
                                            <th class="text-left">Turn</th>
                                            <th class="text-left">Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(member, index) in members" :key="member.id" class="py-3 cursor-pointer hover:bg-violet-100 transition-all ease-in-out duration-300">
                                            <td class="py-3">{{ index + 1 }}</td>
                                            <td>{{ member.fullname }}</td>
                                            <td>{{ member.payout_turn }}</td>
                                            <td>{{ member.joined_at }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else>
                                No members have been added to the saving group.
                            </div>
                            <template v-if="saving.total_members > members.length">
                                <div class="mt-7">Invite new members to join your savings group</div>
                                <div v-if="saving.members.length < saving.total_members" class="flex items-center space-x-6 pb-4 mt-5">
                                    <button @click="shareViaWhatsApp" class="bg-green-600 text-white px-3 py-2 rounded-md mr-2">
                                        <BxWhatsapp class="text-xl"/>
                                    </button>
                                    <button @click="shareViaEmail" class="bg-blue-500 text-white px-3 py-2 rounded-md mr-2">
                                        <IoOutlineMail class="text-xl" />
                                    </button>
                                    <button @click="shareViaText" class="bg-primary text-white px-4 py-2 rounded-md mr-2">
                                        <LaSmsSolid class="text-xl"/>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="p-4 w-full pb-4 space-y-6 bg-white border border-gray-150 rounded-lg shadow-lg mt-4">
                        <div class="w-full text-lg tracking-wide pt-3 font-semibold text-primary px-3">Invites <span v-if="invites?.length > 0" class="chip">{{ invites?.length }}</span></div>
                        <div class="text-gray-600 w-full">
                            <div class="w-full" v-if="invites?.length > 0">
                                <table class="table-auto w-full">
                                    <thead>
                                        <tr>
                                            <th class="text-left">S/N</th>
                                            <th class="text-left">Name</th>
                                            <th class="text-left">Invited On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(invite, index) in invites" :key="invite.id" class="py-3 cursor-pointer hover:bg-violet-100 transition-all ease-in-out duration-300">
                                            <td class="py-3">{{ index + 1 }}</td>
                                            <td>{{ invite.user?.fullname }}</td>
                                            <td>{{ invite.created }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else>
                                No pending invites for this saving group.
                            </div>
                        </div>
                    </div>
                    <div class="p-4 w-full pb-4 space-y-6 bg-white border border-gray-150 rounded-lg shadow-lg mt-4" v-if="saving.payout_turn_method_id != 1">
                        <div class="w-full text-lg tracking-wide pt-3 font-semibold text-primary px-3">Set Payout Turn</div>
                        <template v-if="saving.payout_turn_method_id == 3">
                            <template v-if="!memberTurn">
                                <div class="text-gray-700 px-3 -mt-4">You have not choosen a payout turn. Select your preferred payout turn from the available turns below.</div>
                                <div class="">
                                    <Select v-model="selectedTurn" :options="formattedTurns"  placeholder="Select Payout Turn" value="value" @option-selected="handleTurnSelect">
                                        <template #option="{ option }">
                                            <div class="flex items-center">
                                                <span class="font-semibold">Turn {{ option.label }}</span>
                                            </div>
                                        </template>
                                    </Select>
                                    <div v-if="selectedTurn" class="my-3 p-3">
                                        <button @click="chooseTurn" :disabled="isSubmitting" class="bg-primary text-center text-sm rounded-lg text-white py-2.5 px-5 hover:bg-primary_var smooth_transition">Choose Turn</button>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <div class="text-gray-700 px-3">Your turn has been set. </div>
                            </template>
                        </template>
                        <template v-if="saving.payout_turn_method_id == 4">
                            <template v-if="isCreator">
                                <div class="text-gray-700 px-3 -mt-4" v-if="membersWithNoTurns > 0">Set payout turn for your members.</div>
                                <table class="table-auto w-full" v-if="membersWithNoTurns.length > 0">
                                    <thead>
                                        <tr>
                                            <th class="text-left">S/N</th>
                                            <th class="text-left">Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr @click="goToSetPayoutTurn(member.id)" v-for="(member, index) in membersWithNoTurns" :key="member.id" class="px-3 text-sm hover:bg-violet-100 hover:cursor-pointer smooth_transition">
                                            <td class="py-2.5">{{ index + 1 }}</td>
                                            <td>{{ member.fullname }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div v-else class="px-3 text-gray-700 text-sm">
                                    The payout turn of all your savings members have been set.
                                </div>
                            </template>
                        </template>
                        <!-- {{ memberTurn }} -->
                       <!-- <template v-if="!memberTurn">
                            <div v-if="saving.payout_turn_method_id == 3"> 
                            </div>
                        </template>
                        <template v-else></template> -->
                    </div>
                </div>
                <div class="bg-white p-4 shadow rounded-lg self-start sticky top-0"> 
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
                                    <th class="w-1/2 text-left py-2 space-x-3">Total Payout: </th>
                                    <td>&pound;{{ saving.total_payout }}</td>
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
        <SearchUserModal v-model:isOpen="UserSearchModalOpen" @userSelected="handleUserSelect" />
        <!-- <SearchUserModal v-model:isOpen="isModalOpen" :users="users" @userSelected="handleUserSelect" /> -->
    </AuthLayout>
</template>

<script setup>
import { computed, ref, watch, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout2.vue';
import { CaCloseOutline, AnOutlinedEdit, FlDelete, BxWhatsapp, IoOutlineMail, LaSmsSolid, GlError } from '@kalimahapps/vue-icons';
import { useToast } from "vue-toastification";
import SearchUserModal from '@/CustomComponents/SearchUserModal.vue';
import Select from '@/CustomComponents/CustomSelects.vue';
import { Inertia } from '@inertiajs/inertia';

const toast = useToast();
const UserSearchModalOpen = ref(false)
const isSubmitting = ref(false)

const props = defineProps({
    saving: Object,
    isCreator: Boolean,
    isMember: Boolean,
    userSavings: Array,
    createdSavings: Array,
    members: Array,
    invites: Array,
    availableTurns: Array,
    memberTurn: Number
})

const selectedTurn = ref(null);

const isMember = ref(props.isMember);
const invites = ref(props.invites)
const members = ref(props.members)
const memberTurn = ref(props.memberTurn);

const inviteLink = computed(() => `${window.location.origin}/join/${props.saving.savings_uid}`);

const userToInvite = ref(null)
// const userHasJoined = ref(false)
const isSendingInvite = ref(false)
const inviteError = ref(false)
const inviteErrorMsg = ref('')

const membersWithNoTurns = computed(() => {
  return props.members.filter(member => member.payout_turn === null);
});

const shareViaWhatsApp = () => {
    const message = `Join my saving group: ${inviteLink.value}`;
    window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, '_blank');
};

const shareViaEmail = () => {
    const subject = "Join My Saving Group";
    const body = `Click the link to join: ${inviteLink.value}`;
    window.location.href = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
};

const shareViaText = () => {
    const message = `Join my saving group: ${inviteLink.value}`;
    window.location.href = `sms:?body=${encodeURIComponent(message)}`;
};

const addSelfToGroup = () => {
    axios.post(`/add-creator-to-savings-group/${props.saving.id}`, {})
    .then((res) => {
        console.log(res.data)
        if(res.data.message = 'Member joined successfully'){
            toast.success('You have been added as a Member of this saving group successfully')
            members.value.push({
                fullname: res.data.fullname,
                payout_turn: res.data.payout_turn,
                joined_at: res.data.joined_at
            },)
            isMember.value = true
        }
    })
}

watch(() => props.isMember, (newValue) => {
    isMember.value = newValue;
});

const handleUserSelect = (user) => {
    isSendingInvite.value = true
    const hasJoined = props.members.some(mem => mem.id === user.id);
    const userHasBeenInvited = props.invites.some(inv => inv.user_id === user.id)
    userToInvite.value = user
    if(hasJoined){
        inviteError.value = true
        inviteErrorMsg.value = `${user.fullname} is already a member of this group and cannot be invited again.`
    }else if(userHasBeenInvited){
        inviteError.value = true
        inviteErrorMsg.value = `${user.fullname} has already been invited, you cannot invite them again.`
    }else{
        inviteError.value = false
    }
    // console.log(userHasBeenInvited)
}

const cancelInvite = () => {
    isSendingInvite.value = false
    userToInvite.value = null
}

const sendInvite = () => {
    isSubmitting.value = true
    axios.post('/api/send-savings-membership-invite', {
        saving: props.saving.id,
        user: userToInvite.value.id
    }).then((res) => {
        console.log(res.data)
        isSubmitting.value = false
        isSendingInvite.value = false
        userToInvite.value = null
        props.invites.push(res.data)
        toast.success('The saving membership invite has been sent successfully')
    }).catch(err => {
        if(err){
            isSubmitting.value = false
            toast.error('The saving membership invite failed. Please try again later.')
        }
    })
}

const formattedTurns = computed(() => {
    return props.availableTurns.map(turn => ({
        label: `Turn ${turn}`,  // The display label for the turn
        value: turn   // The actual value (used for the backend)
    }));
});

const handleTurnSelect = (option) => {
    if(option.value){
        selectedTurn.value = option.value
    }
}

const chooseTurn = () => {
    isSubmitting.value = true
    axios.post(`/api/select-payout-turn-for-members-choose/${props.saving.id}`, {
        turn: selectedTurn.value
    }).then((res) => {
            isSubmitting.value = false
            memberTurn.value = selectedTurn.value
            console.log(res.data)
            toast.success('You have successfully choosen a payout turn.')
        })
}

const goToSetPayoutTurn = (userId) => {
    Inertia.visit(`/membership-payout-turn/${props.saving.savings_uid}/${userId}`);
}

// provide('savingId', props.saving.id);
// onMounted(() => {
//     console.log("provide coming from showsaving ", props.saving.id)
// })

</script>
          