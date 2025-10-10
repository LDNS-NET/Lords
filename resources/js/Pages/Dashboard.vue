<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { AlertCircleIcon } from 'lucide-vue-next';

defineProps({
    stats: Object,
    recent_payments: Array,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center space-x-4 justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                Dashboard
            </h2>
            <h2 class="text-xl font-semibold leading-tight">
                <AlertCircleIcon class="w-6 h-6 text-red-600 inline-block ml-2" />
                Realtime updates on dashboard coming soon!
            </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3">
                    <div class="p-6 bg-white rounded-lg shadow">
                        <div class="text-sm font-medium text-gray-500">Total Apartments</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ stats && stats.total_apartments !== undefined ? stats.total_apartments : 0 }}</div>
                    </div>

                    <div class="p-6 bg-white rounded-lg shadow">
                        <div class="text-sm font-medium text-gray-500">Total Renters</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ stats && stats.total_renters !== undefined ? stats.total_renters : 0 }}</div>
                    </div>

                    <div class="p-6 bg-white rounded-lg shadow">
                        <div class="text-sm font-medium text-gray-500">Pending Payments</div>
                        <div class="mt-2 text-3xl font-bold text-yellow-600">{{ stats && stats.pending_payments !== undefined ? stats.pending_payments : 0 }}</div>
                    </div>

                    <div class="p-6 bg-white rounded-lg shadow">
                        <div class="text-sm font-medium text-gray-500">Total Payments Received</div>
                        <div class="mt-2 text-3xl font-bold text-green-600">Ksh{{ stats && stats.total_payments !== undefined ? stats.total_payments : 0 }}</div>
                    </div>

                    <div class="p-6 bg-white rounded-lg shadow">
                        <div class="text-sm font-medium text-gray-500">SMS Sent</div>
                        <div class="mt-2 text-3xl font-bold text-blue-600">{{ stats && stats.sms_sent !== undefined ? stats.sms_sent : 0 }}</div>
                    </div>

                    <div class="p-6 bg-white rounded-lg shadow">
                        <div class="text-sm font-medium text-gray-500">Emails Sent</div>
                        <div class="mt-2 text-3xl font-bold text-purple-600">{{ stats && stats.emails_sent !== undefined ? stats.emails_sent : 0 }}</div>
                    </div>
                </div>

                <!-- Recent Payments -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Recent Payments</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Renter</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Reference</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="payment in recent_payments" :key="payment.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ payment.renter_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Ksh{{ payment.amount }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ payment.reference }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="{
                                            'bg-green-100 text-green-800': payment.status === 'success',
                                            'bg-yellow-100 text-yellow-800': payment.status === 'pending',
                                            'bg-red-100 text-red-800': payment.status === 'failed'
                                        }" class="px-2 py-1 text-xs font-semibold rounded-full">
                                            {{ payment.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ new Date(payment.created_at).toLocaleDateString() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
