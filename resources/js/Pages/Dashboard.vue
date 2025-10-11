<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { AlertCircleIcon } from 'lucide-vue-next'
import { ref, onMounted, onUnmounted } from 'vue'

// Props from controller
const props = defineProps({
  stats: Object,
  recent_payments: Array,
})

// Reactive copies for live updates
const stats = ref(props.stats || {})
const recentPayments = ref(props.recent_payments || [])

// Optional: real-time updates using Laravel Echo (if available)
let interval = null

onMounted(() => {
  // If Laravel Echo is available, use it for realtime updates
  if (window.Echo) {
    try {
      window.Echo.private('dashboard-updates')
        .listen('DashboardUpdated', (event) => {
          stats.value = event.stats
          recentPayments.value = event.recent_payments
        })
    } catch (e) {
      console.warn('Echo not configured, using fallback polling...')
    }
  }

  // Fallback: periodic polling every 20s
  interval = setInterval(async () => {
    try {
      const response = await fetch(route('dashboard.data')) // You’ll create this route in backend
      if (response.ok) {
        const data = await response.json()
        stats.value = data.stats
        recentPayments.value = data.recent_payments
      }
    } catch (error) {
      console.error('Error fetching dashboard data:', error)
    }
  }, 20000)
})

onUnmounted(() => {
  if (interval) clearInterval(interval)
})
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <!-- Header -->
    <template #header>
      <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-100">
          Dashboard
        </h2>
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
          <AlertCircleIcon class="w-5 h-5 text-yellow-500 mr-2" />
          Realtime dashboard updates active
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div
            class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200"
          >
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
              Total Apartments
            </div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
              {{ stats.total_apartments ?? 0 }}
            </div>
          </div>

          <div
            class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200"
          >
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
              Total Renters
            </div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
              {{ stats.total_renters ?? 0 }}
            </div>
          </div>

          <div
            class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200"
          >
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
              Pending Payments
            </div>
            <div class="mt-2 text-3xl font-bold text-yellow-500">
              {{ stats.pending_payments ?? 0 }}
            </div>
          </div>

          <div
            class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200"
          >
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
              Total Payments Received
            </div>
            <div class="mt-2 text-3xl font-bold text-green-500">
              Ksh{{ stats.total_payments ?? 0 }}
            </div>
          </div>

          <div
            class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200"
          >
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
              SMS Sent
            </div>
            <div class="mt-2 text-3xl font-bold text-blue-500">
              {{ stats.sms_sent ?? 0 }}
            </div>
          </div>

          <div
            class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-200"
          >
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
              Emails Sent
            </div>
            <div class="mt-2 text-3xl font-bold text-purple-500">
              {{ stats.emails_sent ?? 0 }}
            </div>
          </div>
        </div>

        <!-- Recent Payments -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
          <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
            Recent Payments
          </h3>
          <div class="overflow-x-auto">
            <table
              class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
            >
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th
                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-600 dark:text-gray-300 uppercase"
                  >
                    Renter
                  </th>
                  <th
                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-600 dark:text-gray-300 uppercase"
                  >
                    Amount
                  </th>
                  <th
                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-600 dark:text-gray-300 uppercase"
                  >
                    Reference
                  </th>
                  <th
                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-600 dark:text-gray-300 uppercase"
                  >
                    Status
                  </th>
                  <th
                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-600 dark:text-gray-300 uppercase"
                  >
                    Date
                  </th>
                </tr>
              </thead>
              <tbody
                class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
              >
                <tr
                  v-for="payment in recentPayments"
                  :key="payment.id"
                  class="hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                >
                  <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">
                    {{ payment.renter_name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">
                    Ksh{{ payment.amount }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">
                    {{ payment.reference }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      :class="{
                        'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200': payment.status === 'success',
                        'bg-yellow-100 dark:bg-yellow-700 text-yellow-800 dark:text-yellow-200': payment.status === 'pending',
                        'bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-200': payment.status === 'failed'
                      }"
                      class="px-2 py-1 text-xs font-semibold rounded-full"
                    >
                      {{ payment.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">
                    {{ new Date(payment.created_at).toLocaleDateString() }}
                  </td>
                </tr>

                <tr v-if="!recentPayments.length">
                  <td
                    colspan="5"
                    class="px-6 py-4 text-center text-gray-500 dark:text-gray-400"
                  >
                    No recent payments available.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
