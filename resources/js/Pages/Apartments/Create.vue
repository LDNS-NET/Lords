<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'

const toast = useToast()

const form = useForm({
  name: '',
  location: '',
  number_of_units: '',
  description: '',
})

const submit = () => {
  form.post(route('apartments.store'), {
    onSuccess: () => {
      toast.success('Apartment created successfully!')
      form.reset()
    },
    onError: () => {
      toast.error('Failed to create apartment. Please check the form for errors.')
    },
  })
}
</script>

<template>
  <Head title="Create Apartment" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Create Apartment
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6">
            <form @submit.prevent="submit">
              <!-- Name -->
              <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input
                  type="text"
                  id="name"
                  v-model="form.name"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  required
                />
                <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</div>
              </div>

              <!-- Location -->
              <div class="mb-4">
                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                <input
                  type="text"
                  id="location"
                  v-model="form.location"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  required
                />
                <div v-if="form.errors.location" class="mt-1 text-sm text-red-600">{{ form.errors.location }}</div>
              </div>

              <!-- Number of Units -->
              <div class="mb-4 block text-sm font-medium text-gray-700">
                <label for="number_of_units" class="block text-sm font-medium">Number of Units</label>
                <input
                  type="number"
                  id="number_of_units"
                  v-model="form.number_of_units"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  required
                />
                <div v-if="form.errors.number_of_units" class="mt-1 text-sm text-red-600">{{ form.errors.number_of_units }}</div>
              </div>

              <!-- Description -->
              <div class="mb-4 block text-sm font-medium text-gray-700">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="4"
                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                ></textarea>
                <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</div>
              </div>

              <!-- Actions -->
              <div class="flex justify-end space-x-3">
                <a
                  :href="route('apartments.index')"
                  class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300"
                >
                  Cancel
                </a>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50"
                >
                  Create Apartment
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
