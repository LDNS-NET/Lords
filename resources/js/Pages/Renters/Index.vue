<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { PlusCircleIcon } from '@heroicons/vue/24/outline';
import { Head, Link, router } from '@inertiajs/vue3';
import { Eye, Pencil, Search, Trash, User2Icon } from 'lucide-vue-next';
import { ref } from 'vue';
import { useToast } from 'vue-toastification';
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';

const toast = useToast();

const props = defineProps({
    renters: Object,
    filters: Object,
});

const showModal = ref(false);
const viewing = ref(null);

const openViewing = (renter) => {
    viewing.value = renter;
};

const search = ref(props.filters.search || '');

const searchRenters = () => {
    router.get(route('renters.index'), { search: search.value }, { preserveState: true });
};


function destroy(renterId) {
    if (confirm('Are you sure you want to delete this renter?')) {
        router.delete(route('renters.destroy', renterId), {
            onSuccess: () => {
                toast.success('Tenant deleted successfully!');
            },
            onError: () => {
                toast.error('Failed to delete tenant. Please try again.');
            },
        });
    }
}

</script>

<template>
    <Head title="Renters" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
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
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 dark:bg-gray-900">
                <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg dark:border dark:border-gray-700">
                    <div class="p-6">
                        <div class="mb-6">
                            <div class="flex gap-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                <input 
                                    v-model="search" 
                                    type="text" 
                                    placeholder="Search by name, email, or phone..."
                                    class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    @keyup.enter="searchRenters"
                                >
                                <button @click="searchRenters" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                    <Search class="w-5 h-5 inline-block ml-1" />
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto ">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border dark:border-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 border-b dark:border-gray-600 border-r dark:border-gray-700">Name</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 border-b dark:border-gray-600 border-r dark:border-gray-700">Email</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 border-b dark:border-gray-600 border-r dark:border-gray-700">Phone</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 border-b dark:border-gray-600 border-r dark:border-gray-700">Apartment</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 border-b dark:border-gray-600 border-r dark:border-gray-700">House No.</th>
                                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 border-b dark:border-gray-600 border-r dark:border-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="renter in renters.data" :key="renter.id" class="dark:hover:bg-gray-700">
                                        <td class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 border-b dark:border-gray-600 border-r dark:border-gray-700">{{ renter.full_name }}</td>
                                        <td class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 border-b dark:border-gray-600 border-r dark:border-gray-700">{{ renter.email }}</td>
                                        <td class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 border-b dark:border-gray-600 border-r dark:border-gray-700">{{ renter.phone_number }}</td>
                                        <td class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 border-b dark:border-gray-600 border-r dark:border-gray-700">{{ renter.apartment?.name || 'N/A' }}</td>
                                        <td class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 dark:text-gray-300 border-b dark:border-gray-600 border-r dark:border-gray-700">{{ renter.house_number }}</td>
                                        <td class="px-6 py-4 space-x-2 whitespace-nowrap bg-white dark:bg-gray-800 dark:text-gray-200">
                                            <button @click="openViewing(renter)" class="text-blue-600 hover:text-blue-300">
                                                <Eye class="w-4 h-auto inline-block" />
                                            </button>
                                            <Link v-if="renter.id" :href="route('renters.edit', renter.id)" class="text-yellow-600 hover:text-yellow-300">
                                                <Pencil class="w-4 h-auto inline-block" />
                                            </Link>
                                            <button @click="destroy(renter.id)" class="text-red-600 hover:text-red-300">
                                                <Trash class="w-4 h-auto inline-block" />
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="renters.data.length === 0">
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800">No renters found</td>
                                    </tr>
                                </tbody>


                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            <Pagination :links="renters.links" />
                        </div>

                        <!-- Viewing Modal -->
                         <Modal :show="!!viewing" @close="viewing = null" >
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Renter Details</h3>
                                <div class="space-y-2">
                                    <div><strong class="font-semibold">Full Name:</strong> {{ viewing?.full_name }}</div>
                                    <div><strong class="font-semibold">Email:</strong> {{ viewing?.email }}</div>
                                    <div><strong class="font-semibold">Phone Number:</strong> {{ viewing?.phone_number }}</div>
                                    <div><strong class="font-semibold">ID Number:</strong> {{ viewing?.id_number }}</div>
                                    <div><strong class="font-semibold">Apartment:</strong> {{ viewing?.apartment?.name || 'N/A' }}</div>
                                    <div><strong class="font-semibold">House Number:</strong> {{ viewing?.house_number }}</div>
                                    <div><strong class="font-semibold">Move In Date:</strong> {{ viewing?.move_in_date ? new Date(viewing.move_in_date).toLocaleDateString() : 'N/A' }}</div>
                                </div>
                                <div class="mt-6 text-right">
                                    <button @click="viewing = null" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
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