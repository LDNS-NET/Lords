<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    payments: Object,
    filters: Object,
});

const statusFilter = ref(props.filters.status || '');

const filterPayments = () => {
    router.get(route('payments.index'), { status: statusFilter.value }, { preserveState: true });
};

const exportPayments = () => {
    window.location.href = route('payments.export');
};
</script>

<template>
    <Head title="Payments" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Payments
                </h2>
                <div class="flex space-x-2">
                    <button @click="exportPayments" class="px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700">
                        Export CSV
                    </button>
                    <Link :href="route('payments.create')" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Create Payment
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Filter Bar -->
                        <div class="mb-6">
                            <div class="flex gap-2">
                                <select v-model="statusFilter" @change="filterPayments"
                                    class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="success">Success</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Renter</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Reference</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Paid At</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="payment in payments.data" :key="payment.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ payment.renter_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold">Ksh {{ payment.amount }}</td>
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ payment.paid_at ? new Date(payment.paid_at).toLocaleString() : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 space-x-2 whitespace-nowrap">
                                            <Link :href="route('payments.show', payment.id)" class="text-blue-600 hover:text-blue-900">View</Link>
                                            <Link :href="route('payments.destroy', payment.id)" method="delete" as="button" class="text-red-600 hover:text-red-900">Delete</Link>
                                        </td>
                                    </tr>
                                    <tr v-if="payments.data.length === 0">
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No payments found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex items-center justify-between mt-6">
                            <div class="text-sm text-gray-700">
                                Showing {{ payments.from }} to {{ payments.to }} of {{ payments.total }} results
                            </div>
                            <div class="flex space-x-2">
                                <template v-for="link in payments.links" :key="link.label">
                                    <Link
                                        v-if="link.url"
                                        :href="link.url"
                                        :class="{'bg-blue-600 text-white': link.active, 'bg-white text-gray-700': !link.active}"
                                        class="px-3 py-2 border rounded-md hover:bg-gray-50"
                                        v-html="link.label"
                                    />
                                    <span
                                        v-else
                                        :class="{'bg-white text-gray-400': true, 'px-3 py-2 border rounded-md': true}"
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
