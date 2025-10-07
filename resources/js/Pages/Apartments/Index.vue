<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    apartments: Object,
});
</script>

<template>
    <Head title="Apartments" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Apartments
                </h2>
                <Link :href="route('apartments.create')" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Add Apartment
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
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Location</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Units</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Renters</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="apartment in apartments.data" :key="apartment.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ apartment.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ apartment.location }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ apartment.number_of_units }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ apartment.renters_count }}</td>
                                        <td class="px-6 py-4 space-x-2 whitespace-nowrap">
                                            <Link :href="route('apartments.show', apartment.id)" class="text-blue-600 hover:text-blue-900">View</Link>
                                            <Link :href="route('apartments.edit', apartment.id)" class="text-yellow-600 hover:text-yellow-900">Edit</Link>
                                            <Link :href="route('apartments.destroy', apartment.id)" method="delete" as="button" class="text-red-600 hover:text-red-900">Delete</Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex items-center justify-between mt-6">
                            <div class="text-sm text-gray-700">
                                Showing {{ apartments.from }} to {{ apartments.to }} of {{ apartments.total }} results
                            </div>
                            <div class="flex space-x-2">
                                <template v-for="link in apartments.links" :key="link.label">
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
