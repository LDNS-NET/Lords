<script setup>

import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { Eye, House, Pencil, Save, Trash, Warehouse } from 'lucide-vue-next';
import { useToast } from 'vue-toastification';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';


const toast = useToast();

defineProps({
    smsLogs: Object,
});

const editing = ref(null)
const showMoadal = ref(false)
const viewing = ref(null)

const form = useForm({
    recipient_name: '',
    phone_number: '',
    message: '',
    status: 'pending',
    sent_at: '',
})

function openEdit(sms) {
    editing.value = sms
    form.recipient_name = sms.recipient_name
    form.phone_number = sms.phone_number
    form.message = sms.message
    form.status = sms.status
    form.sent_at = sms.sent_at
    showMoadal.value = true
}

function remove(id) {
    if (confirm('Are you sure you want to delete this SMS log?')) {
        router.delete(route('sms.destroy', id), {
            onSuccess: () => {
                toast.success('SMS log deleted successfully');
            },
            onError: () => {
                toast.error('Failed to delete SMS log');
            }
        });
    }
}

function view(sms) {
    viewing.value = sms
}



</script>

<template>
    <Head title="SMS Logs" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-100">
                    SMS Logs
                </h2>
                <Link :href="route('sms.create')" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Send SMS
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden dark:bg-gray-800 shadow-sm sm:rounded-lg dark:border dark:border-gray-700">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y dark:divide-gray-700 border dark:border-gray-700">
                                <thead class="dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left dark:text-gray-300 uppercase border-b dark:border-gray-600 border-r">Recipient</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left dark:text-gray-300 uppercase border-b dark:border-gray-600 border-r">Phone</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left dark:text-gray-300 uppercase border-b dark:border-gray-600 border-r">Message</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left dark:text-gray-300 uppercase border-b dark:border-gray-600 border-r">Status</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left dark:text-gray-300 uppercase border-b dark:border-gray-600 border-r">Sent At</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left dark:text-gray-300 uppercase border-b dark:border-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="dark:bg-gray-800 divide-y dark:divide-gray-700">
                                    <tr v-for="sms in smsLogs.data" :key="sms.id">
                                        <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300 border-r dark:border-gray-700">{{ sms.recipient_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300 border-r dark:border-gray-700">
                                            <div class="max-w-xs truncate" :title="sms.phone_number">
                                                {{ sms.phone_number }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 dark:text-gray-300 border-r dark:border-gray-700">
                                            <div class="max-w-xs truncate" :title="sms.message">{{ sms.message }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-r dark:border-gray-700">
                                            <span :class="{
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': sms.status === 'sent',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': sms.status === 'pending',
                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': sms.status === 'failed'
                                            }" class="px-2 py-1 text-xs font-semibold rounded-full relative"
                                            :title="sms.status === 'failed' ? sms.error_message : ''">
                                                {{ sms.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap dark:text-gray-300 border-r dark:border-gray-700">
                                            {{ sms.sent_at ? new Date(sms.sent_at).toLocaleString() : 'N/A' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button @click="view(sms)" class="text-blue-400 hover:text-blue-300 mr-2" title="View">
                                                <Eye class="w-5 h-5" />
                                            </button>
                                            <button @click="remove(sms.id)" class="text-red-400 hover:text-red-300" title="Delete">
                                                <Trash class="w-5 h-5" />
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="smsLogs.data.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center dark:text-gray-400 border-r dark:border-gray-700">No SMS logs found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            <Pagination :links="smsLogs.links" />
                        </div>


                        <!-- View Modal -->
                        <Modal :show="!!viewing" @close="viewing = null">
                            <div class="p-6 dark:bg-gray-800 dark:text-gray-100" v-if="viewing">
                                <h3 class="text-lg font-medium text-gray-100 mb-4">SMS Details</h3>
                                <div class="space-y-4">
                                    <div>
                                        <strong>Recipient:</strong> {{ viewing.recipient_name }}
                                    </div>
                                    <div>
                                        <strong>Phone Number:</strong> {{ viewing.phone_number }}
                                    </div>
                                    <div>
                                        <strong>Message:</strong> {{ viewing.message }}
                                    </div>
                                    <div>
                                        <strong>Status:</strong> {{ viewing.status }}
                                    </div>
                                    <div>
                                        <strong>Sent At:</strong> {{ viewing.sent_at ? new Date(viewing.sent_at).toLocaleString() : 'N/A' }}
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <PrimaryButton @click="viewing = false" type="button">
                                        Close
                                    </PrimaryButton>
                                </div>
                            </div>
                        </Modal>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>