<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useToast } from 'vue-toastification';

const toast = useToast();

const props = defineProps({
    apartments: Array,
});

const form = useForm({
    full_name: '',
    email: '',
    phone_number: '',
    id_number: '',
    apartment_id: '',
    house_number: '',
    move_in_date: '',
});

const submit = () => {
    form.phone_number = String(form.phone_number ?? '');
    form.post(route('renters.store'), {
        onSuccess: () => {
            toast.success('Renter created successfully!');
            form.reset();
        },
        onError: () => {
            toast.error('Failed to create renter. Please check the form for errors.');
        },
    });
};

</script>

<template>
    <Head title="Create Renter" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create Renter
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 block text-sm font-medium text-gray-700">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" id="full_name" v-model="form.full_name" 
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <div v-if="form.errors.full_name" class="mt-1 text-sm text-red-600">{{ form.errors.full_name }}</div>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" id="email" v-model="form.email" 
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</div>
                                </div>

                                <div>
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="text" id="phone_number" v-model="form.phone_number" 
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <div v-if="form.errors.phone_number" class="mt-1 text-sm text-red-600">{{ form.errors.phone_number }}</div>
                                </div>

                                <div>
                                    <label for="id_number" class="block text-sm font-medium text-gray-700">ID Number</label>
                                    <input type="text" id="id_number" v-model="form.id_number" 
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <div v-if="form.errors.id_number" class="mt-1 text-sm text-red-600">{{ form.errors.id_number }}</div>
                                </div>

                                <div>
                                    <label for="apartment_id" class="block text-sm font-medium text-gray-700">Apartment</label>
                                    <select id="apartment_id" v-model="form.apartment_id" 
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Select Apartment</option>
                                        <option v-for="apartment in apartments" :key="apartment.id" :value="apartment.id">
                                            {{ apartment.name }} - {{ apartment.location }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.apartment_id" class="mt-1 text-sm text-red-600">{{ form.errors.apartment_id }}</div>
                                </div>

                                <div>
                                    <label for="house_number" class="block text-sm font-medium text-gray-700">House Number</label>
                                    <input type="text" id="house_number" v-model="form.house_number" 
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <div v-if="form.errors.house_number" class="mt-1 text-sm text-red-600">{{ form.errors.house_number }}</div>
                                </div>

                                <div>
                                    <label for="move_in_date" class="block text-sm font-medium text-gray-700">Move In Date</label>
                                    <input type="date" id="move_in_date" v-model="form.move_in_date" 
                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <div v-if="form.errors.move_in_date" class="mt-1 text-sm text-red-600">{{ form.errors.move_in_date }}</div>
                                </div>
                            </div>

                            <div class="flex justify-end mt-6 space-x-3">
                                <a :href="route('renters.index')" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                    Cancel
                                </a>
                                <button type="submit" :disabled="form.processing" 
                                    class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50">
                                    Create Renter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
