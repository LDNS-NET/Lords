<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    emailLogs: Object,
});
</script>

<template>
    <Head title="Email Logs" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Email Logs
                </h2>
                <Link :href="route('emails.create')" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Send Email
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
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Subject</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Recipients</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Body Preview</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Sent At</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="email in emailLogs.data" :key="email.id">
                                        <td class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 uppercase border-b dark:border-gray-600 border-r dark:border-gray-700">{{ email.subject }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 uppercase border-b dark:border-gray-600 border-r dark:border-gray-700">{{ email.recipients.length }} recipient(s)</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 uppercase border-b dark:border-gray-600 border-r dark:border-gray-700">{{ email.body.replace(/<[^>]*>/g, '') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'bg-green-100 text-green-800': email.status === 'sent',
                                                'bg-yellow-100 text-yellow-800': email.status === 'pending',
                                                'bg-red-100 text-red-800': email.status === 'failed'
                                            }" class="px-2 py-1 text-xs font-semibold rounded-full">
                                                {{ email.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 uppercase border-b dark:border-gray-600 border-r dark:border-gray-700">
                                            {{ email.sent_at ? new Date(email.sent_at).toLocaleString() : 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr v-if="emailLogs.data.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No email logs found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex items-center justify-between mt-6">
                            <div class="text-sm text-gray-700">
                                Showing {{ emailLogs.from }} to {{ emailLogs.to }} of {{ emailLogs.total }} results
                            </div>
                            <div class="flex space-x-2">
                                <template v-for="link in emailLogs.links" :key="link.label">
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
