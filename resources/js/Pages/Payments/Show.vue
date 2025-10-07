<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    payment: Object,
});
</script>

<template>
    <Head title="Payment Details" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Payment Details
                </h2>
                <Link :href="route('payments.index')" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                    Back to Payments
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Payment ID</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">#{{ payment.id }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Renter Name</label>
                                <p class="mt-1 text-lg text-gray-900">{{ payment.renter_name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Amount</label>
                                <p class="mt-1 text-2xl font-bold text-gray-900">Ksh {{ payment.amount }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Payment Reference</label>
                                <p class="mt-1 font-mono text-lg text-gray-900">{{ payment.reference }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Status</label>
                                <span :class="{
                                    'bg-green-100 text-green-800': payment.status === 'success',
                                    'bg-yellow-100 text-yellow-800': payment.status === 'pending',
                                    'bg-red-100 text-red-800': payment.status === 'failed'
                                }" class="inline-block px-3 py-1 mt-1 text-sm font-semibold rounded-full">
                                    {{ payment.status.toUpperCase() }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Created At</label>
                                <p class="mt-1 text-gray-900">{{ new Date(payment.created_at).toLocaleString() }}</p>
                            </div>

                            <div v-if="payment.paid_at">
                                <label class="block text-sm font-medium text-gray-500">Paid At</label>
                                <p class="mt-1 text-gray-900">{{ new Date(payment.paid_at).toLocaleString() }}</p>
                            </div>

                            <div v-if="payment.status === 'pending'" class="p-4 mt-6 bg-yellow-50 rounded-md">
                                <p class="text-sm text-yellow-800">
                                    <strong>Payment Pending:</strong> This payment has not been completed yet. The renter can pay using the reference number above via Paystack.
                                </p>
                            </div>

                            <div v-if="payment.status === 'success'" class="p-4 mt-6 bg-green-50 rounded-md">
                                <p class="text-sm text-green-800">
                                    <strong>Payment Successful:</strong> This payment has been completed and verified.
                                </p>
                            </div>

                            <div v-if="payment.status === 'failed'" class="p-4 mt-6 bg-red-50 rounded-md">
                                <p class="text-sm text-red-800">
                                    <strong>Payment Failed:</strong> This payment could not be processed. Please contact the renter or create a new payment.
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6 space-x-3">
                            <Link :href="route('payments.index')" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                Back to List
                            </Link>
                            <Link v-if="payment.renter" :href="route('renters.show', payment.renter.id)" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                View Renter
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
