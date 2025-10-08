<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { PlusCircleIcon } from '@heroicons/vue/24/outline';
import { Head, Link, router } from '@inertiajs/vue3';
import { Search, User2Icon } from 'lucide-vue-next';
import { ref } from 'vue';
import { useToast } from 'vue3-toastify';

const toast = useToast();

const props = defineProps({
    renters: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

const searchRenters = () => {
    router.get(route('renters.index'), { search: search.value }, { preserveState: true });
};
</script>

<template>
    <Head title="Renters" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    <User2Icon class="w-6 h-6 text-blue-600 inline-block mr-1" />
                    Renters
                </h2>
                <Link :href="route('renters.create')" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    <PlusCircleIcon class="w-5 h-5 inline-block mr-1" />
                    Add Renter
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Search Bar -->
                        <div class="mb-6">
                            <div class="flex gap-2">
                                <input 
                                    v-model="search" 
                                    type="text" 
                                    placeholder="Search by name, email, or phone..."
                                    class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    @keyup.enter="searchRenters"
                                >
                                <button @click="searchRenters" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                   
                                    <Search class="w-5 h-5 inline-block ml-1" />
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Name</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Email</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Phone</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Apartment</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">House No.</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="renter in renters.data" :key="renter.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ renter.full_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ renter.email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ renter.phone_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ renter.apartment?.name || 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ renter.house_number }}</td>
                                        <td class="px-6 py-4 space-x-2 whitespace-nowrap">
                                            <Link v-if="renter.id" :href="route('renters.show', renter.id)" class="text-blue-600 hover:text-blue-900">View</Link>
                                            <span v-else class="text-gray-400">View</span>
                                            <Link v-if="renter.id" :href="route('renters.edit', renter.id)" class="text-yellow-600 hover:text-yellow-900">Edit</Link>
                                            <span v-else class="text-gray-400">Edit</span>
                                            <Link v-if="renter.id" :href="route('renters.destroy', renter.id)" method="delete" as="button" class="text-red-600 hover:text-red-900">Delete</Link>
                                            <span v-else class="text-gray-400">Delete</span>
                                        </td>
                                    </tr>
                                    <tr v-if="renters.data.length === 0">
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No renters found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex items-center justify-between mt-6">
                            <div class="text-sm text-gray-700">
                                Showing {{ renters.from }} to {{ renters.to }} of {{ renters.total }} results
                            </div>
                            <div class="flex space-x-2">
                                <template v-for="link in renters.links" :key="link.label">
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
