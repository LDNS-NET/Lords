<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { Eye, House, Pencil, Save, Trash, Warehouse } from 'lucide-vue-next';
import { useToast } from 'vue-toastification';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const toast = useToast();

defineProps({
    apartments: Object,
});

const editing = ref(null)
const showModal = ref(false)
const viewing = ref(null)

const form = useForm({
    name: '',
    location: '',
    number_of_units: '',
    description: '',
})

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
            onSuccess: () => {
                toast.success('Apartment deleted successfully');
            },
            onError: () => {
                toast.error('Failed to delete apartment');
            },
        });
    }
}

function view(apartment) {
    viewing.value = apartment
}

function submit() {
    if (editing.value) {
        form.put(route('apartments.update', editing.value.id), {
            onSuccess: () => {
                toast.success('Apartment updated successfully');
                showModal.value = false;
                editing.value = null;
                form.reset();
            },
            onError: () => {
                toast.error('Failed to update apartment');
            },
        });
    } else {
        form.post(route('apartments.store'), {
            onSuccess: () => {
                toast.success('Apartment added successfully');
                showModal.value = false;
                form.reset();
            },
            onError: () => {
                toast.error('Failed to add apartment');
            },
        });
    }
}


</script>

<template>
    <Head title="Apartments" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    <Warehouse class="inline w-6 h-6 text-blue-600 mr-1" />
                    Apartments
                </h2>
                <Link :href="route('apartments.create')" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    <House class="inline w-4 h-4 mr-1" />
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
                                        <td class="px-6 py-4 space-x-2 whitespace-nowrap gap-2">
                                            <button @click="view(apartment)" class="text-blue-600 hover:text-green-600"><Eye class="inline-block h-4" /></button>
                                            <button @click="openEdit(apartment)" class="text-green-600 hover:text-blue-600"><Pencil class="inline-block h-4" /></button>
                                            <button @click="remove(apartment.id)" class="text-red-600 hover:text-orange-500"><Trash class="inline-block h-4" /></button>
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

        <!-- editing Modal -->
        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h3 class="text-lg font-extrabold mb-4">
                    {{ editing ? 'Edit Apartment' : 'Add Apartment' }}
                </h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="name" value="Name" />
                        <TextInput id="name" v-model="form.name" class="mt-1 block w-full" required autofocus />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="location" value="Location" />
                        <TextInput id="location" v-model="form.location" class="mt-1 block w-full" required />
                        <InputError :message="form.errors.location" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="number_of_units" value="Number of Units" />
                        <TextInput id="number_of_units" type="number" v-model="form.number_of_units" class="mt-1 block w-full" required />
                        <InputError :message="form.errors.number_of_units" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Description" />
                        <TextInput id="description" v-model="form.description" class="mt-1 block w-full" />
                        <InputError :message="form.errors.description" class="mt-2" />
                    </div>

                    <DangerButton @click="showModal = false" type="button" class="mr-2">
                        Cancel
                    </DangerButton>

                    <PrimaryButton :disabled="form.processing" type="submit">
                        <Save class="inline w-4 h-4 mr-1" />
                        {{ editing ? 'Update' : 'Save' }}
                    </PrimaryButton>

                </form>
            </div>

        </Modal>

        <!-- viewing Modal -->
        <Modal :show="!!viewing" @close="viewing = null">
            <div class="p-6" v-if="viewing">
                <h3 class="text-lg font-extrabold mb-4">
                    Apartment Details
                </h3>
                <div class="space-y-4">
                    <div>
                        <strong class="font-semibold">Name:</strong> {{ viewing.name }}
                    </div>
                    <div>
                        <strong class="font-semibold">Location:</strong> {{ viewing.location }}
                    </div>
                    <div>       
                        <strong class="font-semibold">Number of Units:</strong> {{ viewing.number_of_units }}
                    </div>
                    <div>
                        <strong class="font-semibold">Description:</strong> {{ viewing.description }}
                    </div>
                    <div>
                        <strong class="font-semibold">Renters Count:</strong> {{ viewing.renters_count }}
                    </div>
                </div>
                <div class="mt-4">  
                    <DangerButton @click="showModal = false" type="button">
                        Close
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
