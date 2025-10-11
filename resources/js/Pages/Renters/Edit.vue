<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const toast = useToast();

const props = defineProps({
    renter: Object,
    apartments: Array,
});

const formatDate = (date) => {
    if (!date) return '';
    return date.split('T')[0];
}

const form = useForm({
    full_name: props.renter.full_name || '',
    email: props.renter.email || '',
    phone_number: props.renter.phone_number || '',
    apartment_id: props.renter.apartment_id || '',
    house_number: props.renter.house_number || '',
    id_number: props.renter.id_number || '',
    move_in_date: formatDate(props.renter.move_in_date),
});

function updateRenter() {
    form.put(route('renters.update', props.renter.id), {
        onSuccess: () => {
            toast.success('Tenant updated successfully!');
            router.visit(route('renters.index'));
        },
        onError: () => {
            toast.error('Failed to update tenant. Please check the form for errors.');
        },
    });
}
</script>

<template>
    <Head title="Edit Renter" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
                Edit Tenant
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white dark:bg-gray-900 shadow-sm sm:rounded-lg dark:border dark:border-gray-700">
                    <div class="p-6 text-sm font-medium text-gray-700 dark:text-gray-200">
                        <form @submit.prevent="updateRenter">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <!-- Full Name -->
                                <div>
                                    <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                                    <input
                                        type="text"
                                        id="full_name"
                                        v-model="form.full_name"
                                        class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    />
                                    <div v-if="form.errors.full_name" class="mt-1 text-sm text-red-600">{{ form.errors.full_name }}</div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                    <input
                                        type="email"
                                        id="email"
                                        v-model="form.email"
                                        class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    />
                                    <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</div>
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                                    <input
                                        type="text"
                                        id="phone_number"
                                        v-model="form.phone_number"
                                        class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    />
                                    <div v-if="form.errors.phone_number" class="mt-1 text-sm text-red-600">{{ form.errors.phone_number }}</div>
                                </div>

                                <!-- Apartment -->
                                <div>
                                    <label for="apartment_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Apartment</label>
                                    <select
                                        id="apartment_id"
                                        v-model="form.apartment_id"
                                        class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    >
                                        <option value="">Select Apartment</option>
                                        <option v-for="apartment in apartments" :key="apartment.id" :value="apartment.id">
                                            {{ apartment.name }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.apartment_id" class="mt-1 text-sm text-red-600">{{ form.errors.apartment_id }}</div>
                                </div>

                                <!-- House Number -->
                                <div>
                                    <label for="house_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">House Number</label>
                                    <input
                                        type="text"
                                        id="house_number"
                                        v-model="form.house_number"
                                        class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required
                                    />
                                    <div v-if="form.errors.house_number" class="mt-1 text-sm text-red-600">{{ form.errors.house_number }}</div>
                                </div>
                            

                            <!-- ID Number -->
                            <div>
                                <label for="id_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID Number</label>
                                <input
                                    type="text"
                                    id="id_number"
                                    v-model="form.id_number"
                                    class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <div v-if="form.errors.id_number" class="mt-1 text-sm text-red-600">{{ form.errors.id_number }}</div>
                            </div>

                            <!-- Move-in Date -->
                            <div>
                                <label for="move_in_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Move-in Date</label>
                                <input  
                                    type="date"
                                    id="move_in_date"
                                    v-model="form.move_in_date"
                                    class="block w-full mt-1 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <div v-if="form.errors.move_in_date" class="mt-1 text-sm text-red-600">{{ form.errors.move_in_date }}</div>
                            </div>

                            </div>


                            <!-- Actions -->
                            <div class="flex justify-end mt-6 space-x-3">
                                <Link
                                    :href="route('renters.index')"
                                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50"
                                >
                                    Update Tenant
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
