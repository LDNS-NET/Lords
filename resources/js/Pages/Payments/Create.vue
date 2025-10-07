<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    renters: Array,
});

const form = useForm({
    renter_id: '',
    amount: '',
});

const submit = () => {
    form.post(route('payments.store'));
};
</script>

<template>
    <Head title="Create Payment" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create Payment
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="renter_id" class="block text-sm font-medium text-gray-700">Renter</label>
                                <select id="renter_id" v-model="form.renter_id" 
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Select Renter</option>
                                    <option v-for="renter in renters" :key="renter.id" :value="renter.id">
                                        {{ renter.full_name }} - {{ renter.apartment?.name }} ({{ renter.house_number }})
                                    </option>
                                </select>
                                <div v-if="form.errors.renter_id" class="mt-1 text-sm text-red-600">{{ form.errors.renter_id }}</div>
                            </div>

                            <div class="mb-4">
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500">/=</span>
                                    </div>
                                    <input type="number" id="amount" v-model="form.amount" step="0.01" min="0"
                                        class="block w-full pl-7 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="0.00" required>
                                </div>
                                <div v-if="form.errors.amount" class="mt-1 text-sm text-red-600">{{ form.errors.amount }}</div>
                            </div>

                            <div class="p-4 mb-4 bg-blue-50 rounded-md">
                                <p class="text-sm text-blue-800">
                                    <strong>Note:</strong> This will create a payment record. The renter can then pay via Paystack using the generated reference.
                                </p>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a :href="route('payments.index')" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                    Cancel
                                </a>
                                <button type="submit" :disabled="form.processing" 
                                    class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50">
                                    Create Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
