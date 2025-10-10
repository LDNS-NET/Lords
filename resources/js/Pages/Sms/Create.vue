<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    renters: Array,
});

const form = useForm({
    recipients: [],
    message: '',
});

const selectAll = ref(false);

const toggleSelectAll = () => {
    if (selectAll.value) {
        form.recipients = props.renters.map(r => r.id);
    } else {
        form.recipients = [];
    }
};

const submit = () => {
  console.log('SUBMITTING', { recipients: form.recipients, message: form.message });

  form.post(route('sms.store'), {
    onStart: () => console.log('request started'),
    onSuccess: (page) => console.log('success', page),
    onError: (errors) => console.log('validation errors', errors),
    onFinish: () => console.log('request finished'),
  });
};

</script>

<template>
    <Head title="Send SMS" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Send SMS
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Recipients</label>
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="selectAll" @change="toggleSelectAll" 
                                            class="mr-2 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-sm text-gray-600">Select All</span>
                                    </label>
                                </div>
                                <div class="p-4 space-y-2 border border-gray-300 rounded-md max-h-64 overflow-y-auto">
                                    <label v-for="renter in renters" :key="renter.id" class="flex items-center">
                                        <input type="checkbox" :value="renter.id" v-model="form.recipients" 
                                            class="mr-2 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-sm">{{ renter.full_name }} ({{ renter.phone_number }})</span>
                                    </label>
                                    <div v-if="renters.length === 0" class="text-sm text-gray-500">
                                        No renters available. Please add renters first.
                                    </div>
                                </div>
                                <div v-if="form.errors.recipients" class="mt-1 text-sm text-red-600">{{ form.errors.recipients }}</div>
                            </div>

                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                                <textarea id="message" v-model="form.message" rows="6"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Type your message here..." required></textarea>
                                <div class="mt-1 text-sm text-gray-500">{{ form.message.length }} / 500 characters</div>
                                <div v-if="form.errors.message" class="mt-1 text-sm text-red-600">{{ form.errors.message }}</div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a :href="route('sms.index')" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                    Cancel
                                </a>
                                <button type="submit" :disabled="form.processing || form.recipients.length === 0" 
                                    class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50">
                                    Send SMS
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
