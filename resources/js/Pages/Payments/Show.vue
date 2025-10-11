<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'

const toast = useToast()

defineProps({
  payment: Object,
})

const copyReference = (ref) => {
  navigator.clipboard.writeText(ref)
  toast.success('Payment reference copied!')
}
</script>

<template>
  <Head title="Payment Details" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
          Payment Details
        </h2>
        <Link
          :href="route('payments.index')"
          class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600"
        >
          Back to Payments
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 dark:text-gray-100"
        >
          <div class="p-6 space-y-5">
            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400"
                >Payment ID</label
              >
              <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                #{{ payment.id }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400"
                >Renter Name</label
              >
              <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">
                {{ payment.renter_name }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400"
                >Amount</label
              >
              <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                Ksh {{ payment.amount }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400"
                >Payment Reference</label
              >
              <div class="flex items-center gap-2">
                <p class="mt-1 font-mono text-lg text-gray-900 dark:text-white">
                  {{ payment.reference }}
                </p>
                <button
                  @click="copyReference(payment.reference)"
                  class="px-2 py-1 text-xs text-white bg-gray-700 rounded hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-500"
                >
                  Copy
                </button>
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400"
                >Status</label
              >
              <span
                :class="[
                  'inline-block px-3 py-1 mt-1 text-sm font-semibold rounded-full',
                  payment.status === 'success'
                    ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100'
                    : payment.status === 'pending'
                    ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100'
                    : 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100',
                ]"
              >
                {{ payment.status.toUpperCase() }}
              </span>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400"
                >Created At</label
              >
              <p class="mt-1 text-gray-900 dark:text-gray-100">
                {{ new Date(payment.created_at).toLocaleString() }}
              </p>
            </div>

            <div v-if="payment.paid_at">
              <label class="block text-sm font-medium text-gray-500 dark:text-gray-400"
                >Paid At</label
              >
              <p class="mt-1 text-gray-900 dark:text-gray-100">
                {{ new Date(payment.paid_at).toLocaleString() }}
              </p>
            </div>

            <!-- Status Messages -->
            <div
              v-if="payment.status === 'pending'"
              class="p-4 mt-6 rounded-md bg-yellow-50 dark:bg-yellow-900/40"
            >
              <p class="text-sm text-yellow-800 dark:text-yellow-200">
                <strong>Payment Pending:</strong> This payment has not been
                completed yet. The renter can pay using the reference number
                above via Paystack.
              </p>
            </div>

            <div
              v-if="payment.status === 'success'"
              class="p-4 mt-6 rounded-md bg-green-50 dark:bg-green-900/40"
            >
              <p class="text-sm text-green-800 dark:text-green-200">
                <strong>Payment Successful:</strong> This payment has been
                completed and verified.
              </p>
            </div>

            <div
              v-if="payment.status === 'failed'"
              class="p-4 mt-6 rounded-md bg-red-50 dark:bg-red-900/40"
            >
              <p class="text-sm text-red-800 dark:text-red-200">
                <strong>Payment Failed:</strong> This payment could not be
                processed. Please contact the renter or create a new payment.
              </p>
            </div>

            <!-- Footer Buttons -->
            <div class="flex justify-end mt-6 space-x-3">
              <Link
                :href="route('payments.index')"
                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600"
              >
                Back to List
              </Link>
              <Link
                v-if="payment.renter"
                :href="route('renters.show', payment.renter.id)"
                class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700"
              >
                View Renter
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
