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
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
                    Email Logs
                </h2>
                <Link
                    :href="route('emails.create')"
                    class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600"
                >
                    Send Email
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white dark:bg-gray-900 shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-600 dark:text-gray-300 uppercase">
                                            Subject
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-600 dark:text-gray-300 uppercase">
                                            Recipients
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-600 dark:text-gray-300 uppercase">
                                            Body Preview
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-600 dark:text-gray-300 uppercase">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-600 dark:text-gray-300 uppercase">
                                            Sent At
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="email in emailLogs.data" :key="email.id" class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-200">
                                            {{ email.subject }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                            {{ email.recipients.length }} recipient(s)
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            {{ email.body.replace(/<[^>]*>/g, '') }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="{
                                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': email.status === 'sent',
                                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': email.status === 'pending',
                                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': email.status === 'failed'
                                                }"
                                                class="px-2 py-1 text-xs font-semibold rounded-full"
                                            >
                                                {{ email.status }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            {{ email.sent_at ? new Date(email.sent_at).toLocaleString() : 'N/A' }}
                                        </td>
                                    </tr>

                                    <tr v-if="emailLogs.data.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No email logs found
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex items-center justify-between mt-6">
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                Showing {{ emailLogs.from }} to {{ emailLogs.to }} of {{ emailLogs.total }} results
                            </div>

                            <div class="flex space-x-2">
                                <template v-for="link in emailLogs.links" :key="link.label">
                                    <Link
                                        v-if="link.url"
                                        :href="link.url"
                                        :class="{
                                            'bg-blue-600 text-white dark:bg-blue-700 dark:hover:bg-blue-600': link.active,
                                            'bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700': !link.active
                                        }"
                                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium transition"
                                        v-html="link.label"
                                    />
                                    <span
                                        v-else
                                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-400 dark:text-gray-500 cursor-not-allowed"
                                        v-html="link.label"
                                    />
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
