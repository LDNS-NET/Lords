<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

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
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
        Create Apartment
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
          <div class="p-6 space-y-6">
            <form @submit.prevent="submit" class="space-y-6">
              
              <!-- Name -->
              <div>
                <InputLabel for="name" value="Name" />
                <TextInput
                  id="name"
                  v-model="form.name"
                  type="text"
                  class="mt-1 block w-full"
                  required
                />
                <InputError :message="form.errors.name" class="mt-2" />
              </div>

              <!-- Location -->
              <div>
                <InputLabel for="location" value="Location" />
                <TextInput
                  id="location"
                  v-model="form.location"
                  type="text"
                  class="mt-1 block w-full"
                  required
                />
                <InputError :message="form.errors.location" class="mt-2" />
              </div>

              <!-- Number of Units -->
              <div>
                <InputLabel for="number_of_units" value="Number of Units" />
                <TextInput
                  id="number_of_units"
                  v-model="form.number_of_units"
                  type="text"
                  min="1"
                  class="mt-1 block w-full"
                  required
                />
                <InputError :message="form.errors.number_of_units" class="mt-2" />
              </div>

              <!-- Description -->
              <div>
                <InputLabel for="description" value="Description" />
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="4"
                  class="mt-1 block block w-full"
                ></textarea>
                <InputError :message="form.errors.description" class="mt-2" />
              </div>

              <!-- Actions -->
              <div class="flex justify-end space-x-3">
                <a
                  :href="route('apartments.index')"
                  class="px-4 py-2 text-gray-700 dark:text-gray-200 bg-gray-200 dark:bg-gray-700 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                >
                  Cancel
                </a>
                <PrimaryButton :disabled="form.processing">
                  Create Apartment
                </PrimaryButton>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
