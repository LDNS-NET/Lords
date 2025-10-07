<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    renter: Object,
    apartments: Array,
});

const form = ref({
    full_name: props.renter.full_name,
    email: props.renter.email,
    phone_number: props.renter.phone_number,
    apartment_id: props.renter.apartment_id,
    house_number: props.renter.house_number,
});

const updateRenter = () => {
    // Add your update logic here, e.g. use Inertia form post/put
};
</script>

<template>
    <Head title="Edit Renter" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Renter
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white shadow rounded-lg p-6">
                    <form @submit.prevent="updateRenter">
                        <div class="mb-4">
                            <label class="block text-gray-700">Name</label>
                            <input v-model="form.full_name" class="w-full border rounded px-3 py-2" />
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Email</label>
                            <input v-model="form.email" class="w-full border rounded px-3 py-2" />
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Phone</label>
                            <input v-model="form.phone_number" class="w-full border rounded px-3 py-2" />
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Apartment</label>
                            <select v-model="form.apartment_id" class="w-full border rounded px-3 py-2">
                                <option v-for="apartment in apartments" :key="apartment.id" :value="apartment.id">
                                    {{ apartment.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">House No.</label>
                            <input v-model="form.house_number" class="w-full border rounded px-3 py-2" />
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                        <Link :href="route('renters.index')" class="ml-4 px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</Link>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
