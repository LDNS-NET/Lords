<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import {
  User,
  Banknote,
  MessageSquare,
  Building2,
  Activity,
  ArrowRight,
} from 'lucide-vue-next'
import SuperAdminLayout from '@/Layouts/SuperAdminLayout.vue'

const page = usePage()
const props = computed(() => page.props ?? {})

// Extract specific props
const stats = computed(() => [
  {
    title: 'Registered Tenants',
    value: props.value?.totalApartments ?? 0,
    icon: Building2,
    color: 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300',
    link: route('superadmin.tenants.index'),
  },
  {
    title: 'Users',
    value: props.value?.totalUsers ?? 0,
    icon: User,
    color: 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300',
    link: route('superadmin.users.index'),
  },
  {
    title: 'Payments',
    value: `KSh ${Number(props.value?.totalPayments || 0).toLocaleString()}`,
    icon: Banknote,
    color:
      'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300',
    link: route('superadmin.payments.index'),
  },
  {
    title: 'SMS Sent',
    value: props.value?.totalSms ?? 0,
    icon: MessageSquare,
    color:
      'bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-300',
    link: route('superadmin.sms.index'),
  },
])

// ✅ Safely access recentActivity
const recentActivity = computed(() => props.value?.recentActivity ?? [])
</script>

<template>
  <Head title="Super Admin Dashboard" />

  <SuperAdminLayout>
    <template #header>
      <h2 class="text-2xl font-semibold leading-tight">
        Super Admin Dashboard
      </h2>
    </template>

    <div class="space-y-6">
      <!-- ✅ Stats Cards -->
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div
          v-for="(item, index) in stats"
          :key="index"
          class="p-5 rounded-2xl shadow bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all"
        >
          <div class="flex justify-between items-center mb-3">
            <div :class="['p-2 rounded-lg', item.color]">
              <component :is="item.icon" class="h-6 w-6" />
            </div>
            <Link
              :href="item.link"
              class="text-sm text-gray-500 hover:text-blue-600 dark:text-gray-400"
            >
              <ArrowRight class="h-4 w-4 inline" />
            </Link>
          </div>

          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">
            {{ item.title }}
          </h3>
          <p class="text-2xl font-bold mt-1">{{ item.value }}</p>
        </div>
      </div>

      <!-- ✅ Recent Activity -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div
          class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-100 dark:border-gray-700"
        >
          <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold">Recent Activity</h3>
            <Activity class="h-5 w-5 text-gray-500 dark:text-gray-400" />
          </div>

          <!-- Optional debug line -->
          <!-- <pre class="text-xs text-gray-400">{{ recentActivity }}</pre> -->

          <ul
            v-if="recentActivity.length"
            class="space-y-3 text-sm"
          >
            <li
              v-for="(item, index) in recentActivity"
              :key="index"
              class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2"
            >
              <span v-html="item.message"></span>
              <span class="text-gray-500 dark:text-gray-400 text-xs">
                {{ item.time }}
              </span>
            </li>
          </ul>

          <div
            v-else
            class="text-gray-500 dark:text-gray-400 text-sm text-center py-4"
          >
            No recent activity yet.
          </div>
        </div>
      </div>
    </div>
  </SuperAdminLayout>
</template>
