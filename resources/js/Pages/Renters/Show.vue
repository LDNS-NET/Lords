<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    renter: Object,
});
</script>

<template>
    <Head title="Renter Details" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Renter Details
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="mb-4">
                        <strong>Name:</strong> {{ renter.full_name }}
                    </div>
                    <div class="mb-4">
                        <strong>Email:</strong> {{ renter.email }}
                    </div>
                    <div class="mb-4">
                        <strong>Phone:</strong> {{ renter.phone_number }}
                    </div>
                    <div class="mb-4">
                        <strong>Apartment:</strong> {{ renter.apartment?.name || 'N/A' }}
                    </div>
                    <div class="mb-4">
                        <strong>House No.:</strong> {{ renter.house_number }}
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
