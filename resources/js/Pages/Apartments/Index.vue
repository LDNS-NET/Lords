<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import { Eye, House, Pencil, Save, Trash, Warehouse } from 'lucide-vue-next'
import { useToast } from 'vue-toastification'
import Modal from '@/Components/Modal.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import DangerButton from '@/Components/DangerButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import Pagination from '@/Components/Pagination.vue'

const toast = useToast()

const props = defineProps({
    apartments: Object,
    perPage: Number,
})

const editing = ref(null)
const viewing = ref(null)
const showModal = ref(false)

const form = useForm({
    name: '',
    location: '',
    number_of_units: '',
    description: '',
})

function openCreate() {
    editing.value = null
    form.reset()
    showModal.value = true
}

function openEdit(apartment) {
    editing.value = apartment
    form.name = apartment.name
    form.location = apartment.location
    form.number_of_units = apartment.number_of_units
    form.description = apartment.description
    showModal.value = true
}

function remove(id) {
    if (confirm('Are you sure you want to delete this apartment?')) {
        router.delete(route('apartments.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Apartment deleted successfully')
            },
            onError: () => {
                toast.error('Failed to delete apartment')
            },
        })
    }
}

function view(apartment) {
    viewing.value = apartment
}

function submit() {
    if (editing.value) {
        form.put(route('apartments.update', editing.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Apartment updated successfully')
                showModal.value = false
                editing.value = null
                form.reset()
            },
            onError: () => {
                toast.error('Failed to update apartment')
            },
        })
    } else {
        form.post(route('apartments.store'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Apartment added successfully')
                showModal.value = false
                form.reset()
            },
            onError: () => {
                toast.error('Failed to add apartment')
            },
        })
    }
}
</script>

<template>

    <Head title="Apartments" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
                    <Warehouse class="inline w-6 h-6 text-blue-600 dark:text-blue-400 mr-1" />
                    Apartments
                </h2>
                <button @click="openCreate" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    <House class="inline w-4 h-4 mr-1" />
                    Add Apartment
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-xs font-medium text-left text-gray-600 dark:text-gray-300 uppercase">
                                            Name</th>
                                        <th
                                            class="px-6 py-3 text-xs font-medium text-left text-gray-600 dark:text-gray-300 uppercase">
                                            Location</th>
                                        <th
                                            class="px-6 py-3 text-xs font-medium text-left text-gray-600 dark:text-gray-300 uppercase">
                                            Units</th>
                                        <th
                                            class="px-6 py-3 text-xs font-medium text-left text-gray-600 dark:text-gray-300 uppercase">
                                            Renters</th>
                                        <th
                                            class="px-6 py-3 text-xs font-medium text-left text-gray-600 dark:text-gray-300 uppercase">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="apartment in apartments.data" :key="apartment.id"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ apartment.name }}</td>
                                        <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{ apartment.location }}
                                        </td>
                                        <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{
                                            apartment.number_of_units }}
                                        </td>
                                        <td class="px-6 py-3 text-gray-800 dark:text-gray-100">{{
                                            apartment.renters_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap space-x-3">
                                            <button @click="view(apartment)" class="text-blue-600 hover:text-blue-400">
                                                <Eye class="inline-block h-4 w-4" />
                                            </button>
                                            <button @click="openEdit(apartment)"
                                                class="text-green-600 hover:text-green-400">
                                                <Pencil class="inline-block h-4 w-4" />
                                            </button>
                                            <button @click="remove(apartment.id)"
                                                class="text-red-600 hover:text-red-400">
                                                <Trash class="inline-block h-4 w-4" />
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <Pagination :links="apartments.links" :perPage="perPage" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h3 class="text-xl font-extrabold mb-4 text-gray-900 dark:text-gray-100">
                    {{ editing ? 'Edit Apartment' : 'Add Apartment' }}
                </h3>

                <form @submit.prevent="submit" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <InputLabel for="name" value="Name" class="dark:text-gray-200" />
                        <TextInput id="name" v-model="form.name"
                            class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700 dark:focus:border-green-500 dark:focus:ring-green-500"
                            required />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>

                    <!-- Location -->
                    <div>
                        <InputLabel for="location" value="Location" class="dark:text-gray-200" />
                        <TextInput id="location" v-model="form.location"
                            class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700 dark:focus:border-green-500 dark:focus:ring-green-500"
                            required />
                        <InputError :message="form.errors.location" class="mt-2" />
                    </div>

                    <!-- Units -->
                    <div>
                        <InputLabel for="number_of_units" value="Number of Units" class="dark:text-gray-200" />
                        <TextInput id="number_of_units" type="number" v-model="form.number_of_units"
                            class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700 dark:focus:border-green-500 dark:focus:ring-green-500"
                            required />
                        <InputError :message="form.errors.number_of_units" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div>
                        <InputLabel for="description" value="Description" class="dark:text-gray-200" />
                        <TextInput id="description" v-model="form.description"
                            class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700 dark:focus:border-green-500 dark:focus:ring-green-500" />
                        <InputError :message="form.errors.description" class="mt-2" />
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-2 pt-4">
                        <DangerButton type="button" @click="showModal = false"
                            class="dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-100">
                            Cancel
                        </DangerButton>

                        <PrimaryButton :disabled="form.processing" type="submit"
                            class="dark:bg-green-700 dark:hover:bg-green-600 dark:text-gray-100">
                            <Save class="inline w-4 h-4 mr-1" />
                            {{ editing ? 'Update' : 'Save' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>


        <!-- View Modal -->
        <Modal :show="!!viewing" @close="viewing = null">
            <div class="p-6" v-if="viewing">
                <h3 class="text-lg font-extrabold mb-4 dark:text-gray-100">Apartment Details</h3>
                <div class="space-y-3 text-gray-700 dark:text-gray-100">
                    <p><strong>Name:</strong> {{ viewing.name }}</p>
                    <p><strong>Location:</strong> {{ viewing.location }}</p>
                    <p><strong>Units:</strong> {{ viewing.number_of_units }}</p>
                    <p><strong>Description:</strong> {{ viewing.description }}</p>
                    <p><strong>Renters Count:</strong> {{ viewing.renters_count }}</p>
                </div>
                <div class="mt-6 text-right">
                    <DangerButton type="button" @click="viewing = null">Close</DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
