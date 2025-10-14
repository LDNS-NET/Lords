<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { PlusCircleIcon } from '@heroicons/vue/24/outline';
import { Head, Link, router } from '@inertiajs/vue3';
import { Eye, Pencil, Search, Trash, User2Icon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification';
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';

const toast = useToast();

const props = defineProps({
    renters: Object,
    filters: Object,
    perPage: Number,
});

const viewing = ref(null);
const search = ref(props.filters.search || '');

const openViewing = (renter) => {
    viewing.value = renter;
};

// 🔍 Debounced search
let timeout = null;
watch(search, (value) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(route('renters.index'), { search: value }, {
            preserveState: true,
            preserveScroll: true,
        });
    }, 400);
});

function destroy(renterId) {
    if (confirm('Are you sure you want to delete this renter?')) {
        router.delete(route('renters.destroy', renterId), {
            onSuccess: () => toast.success('Renter deleted successfully!'),
            onError: () => toast.error('Failed to delete renter. Please try again.'),
        });
    }
}
</script>

<template>
    <Head title="Renters" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100 flex items-center">
                    <User2Icon class="w-6 h-6 text-blue-600 mr-2" />
                    Tenants
                </h2>
                <Link
                    :href="route('renters.create')"
                    class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 flex items-center"
                >
                    <PlusCircleIcon class="w-5 h-5 mr-2" />
                    Add Tenant
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 ">
                <div
                    class="overflow-hidden border-blue-400 dark:border-x-blue-400 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border dark:border-gray-700"
                >
                    <div class="p-6">
                        <!-- Search -->
                        <div class="mb-6 flex flex-col sm:flex-row gap-3 sm:items-center">
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Search by name, email, or phone..."
                                class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2"
                            />
                            <button
                                @click="router.get(route('renters.index'), { search }, { preserveState: true, preserveScroll: true })"
                                class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 flex items-center justify-center"
                            >
                                <Search class="w-5 h-5" />
                            </button>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table
                                class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border dark:border-gray-700"
                            >
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium uppercase text-gray-600 dark:text-gray-300 border-r dark:border-gray-700 text-left">Name</th>
                                        <th class="px-6 py-3 text-xs font-medium uppercase text-gray-600 dark:text-gray-300 border-r dark:border-gray-700 text-left">Email</th>
                                        <th class="px-6 py-3 text-xs font-medium uppercase text-gray-600 dark:text-gray-300 border-r dark:border-gray-700 text-left">Phone</th>
                                        <th class="px-6 py-3 text-xs font-medium uppercase text-gray-600 dark:text-gray-300 border-r dark:border-gray-700 text-left">Apartment</th>
                                        <th class="px-6 py-3 text-xs font-medium uppercase text-gray-600 dark:text-gray-300 border-r dark:border-gray-700 text-left">House No.</th>
                                        <th class="px-6 py-3 text-xs font-medium uppercase text-gray-600 dark:text-gray-300 text-left">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr
                                        v-for="renter in renters.data"
                                        :key="renter.id"
                                        class="hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                    >
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 border-r dark:border-gray-700">{{ renter.full_name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 border-r dark:border-gray-700">{{ renter.email }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 border-r dark:border-gray-700">{{ renter.phone_number }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 border-r dark:border-gray-700">{{ renter.apartment?.name || 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 border-r dark:border-gray-700">{{ renter.house_number }}</td>
                                        <td class="px-6 py-4 flex items-center gap-3 text-gray-600 dark:text-gray-300">
                                            <button @click="openViewing(renter)" class="hover:text-blue-500">
                                                <Eye class="w-4 h-4 text-blue-500 hover:text-cyan-300" />
                                            </button>
                                            <Link
                                                :href="route('renters.edit', renter.id)"
                                                class="hover:text-yellow-500"
                                            >
                                                <Pencil class="w-4 h-4 text-yellow-500 hover:text-yellow-400" />
                                            </Link>
                                            <button @click="destroy(renter.id)" class="hover:text-red-500">
                                                <Trash class="w-4 h-4 text-red-500 hover:text-red-400" />
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="renters.data.length === 0">
                                        <td
                                            colspan="6"
                                            class="px-6 py-4 text-center text-gray-500 dark:text-gray-400"
                                        >
                                            No Tenants found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            <Pagination :links="renters.links" :perPage="perPage" />
                        </div>

                        <!-- Viewing Modal -->
                        <Modal :show="!!viewing" @close="viewing = null">
                            <div class="p-6 dark:bg-gray-800 dark:text-gray-100 rounded-lg">
                                <h3 class="text-lg font-semibold mb-4">Tenant Details</h3>
                                <div class="space-y-2 text-sm">
                                    <div><strong>Name:</strong> {{ viewing?.full_name }}</div>
                                    <div><strong>Email:</strong> {{ viewing?.email }}</div>
                                    <div><strong>Phone:</strong> {{ viewing?.phone_number }}</div>
                                    <div><strong>ID Number:</strong> {{ viewing?.id_number }}</div>
                                    <div><strong>Apartment:</strong> {{ viewing?.apartment?.name || 'N/A' }}</div>
                                    <div><strong>House No.:</strong> {{ viewing?.house_number }}</div>
                                    <div><strong>Move-in:</strong> {{ viewing?.move_in_date ? new Date(viewing.move_in_date).toLocaleDateString() : 'N/A' }}</div>
                                </div>
                                <div class="mt-6 text-right">
                                    <button
                                        @click="viewing = null"
                                        class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700"
                                    >
                                        Close
                                    </button>
                                </div>
                            </div>
                        </Modal>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
