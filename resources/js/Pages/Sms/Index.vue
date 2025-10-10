<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    smsLogs: Object,
});
</script>

<template>
    <Head title="SMS Logs" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    SMS Logs
                </h2>
                <Link :href="route('sms.create')" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Send SMS
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Recipient</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Phone</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Message</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Sent At</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="sms in smsLogs.data" :key="sms.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ sms.recipient_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="max-w-xs truncate" :title="sms.phone_number">
                                                {{ sms.phone_number }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="max-w-xs truncate" :title="sms.message">{{ sms.message }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'bg-green-100 text-green-800': sms.status === 'sent',
                                                'bg-yellow-100 text-yellow-800': sms.status === 'pending',
                                                'bg-red-100 text-red-800': sms.status === 'failed'
                                            }" class="px-2 py-1 text-xs font-semibold rounded-full relative"
                                            :title="sms.status === 'failed' ? sms.error_message : ''">
                                                {{ sms.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ sms.sent_at ? new Date(sms.sent_at).toLocaleString() : 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr v-if="smsLogs.data.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No SMS logs found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex items-center justify-between mt-6">
                            <div class="text-sm text-gray-700">
                                Showing {{ smsLogs.from }} to {{ smsLogs.to }} of {{ smsLogs.total }} results
                            </div>
                            <div class="flex space-x-2">
                                <template v-for="link in smsLogs.links" :key="link.label">
                                    <Link v-if="link.url" :href="link.url"
                                        :class="{'bg-blue-600 text-white': link.active, 'bg-white text-gray-700': !link.active}"
                                        class="px-3 py-2 border rounded-md hover:bg-gray-50"
                                        v-html="link.label">
                                    </Link>
                                    <span v-else
                                        :class="{'bg-blue-100 text-blue-600': link.active, 'bg-gray-100 text-gray-400': !link.active}"
                                        class="px-3 py-2 border rounded-md cursor-not-allowed"
                                        v-html="link.label">
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
