<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="hidden md:flex flex-col w-64 h-screen bg-white border-r border-gray-200 py-8 px-4 space-y-4 fixed">
      <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>
      <NavLink :href="route('apartments.index')" :active="route().current('apartments.index')">Apartments</NavLink>
      <NavLink :href="route('renters.index')" :active="route().current('renters.index')">Renters</NavLink>
      <NavLink :href="route('payments.index')" :active="route().current('payments.index')">Payments</NavLink>
      <NavLink :href="route('sms.index')" :active="route().current('sms.index')">SMS</NavLink>
      <NavLink :href="route('emails.index')" :active="route().current('emails.index')">Emails</NavLink>
    </aside>

    <!-- Main content -->
    <div class="flex-1 ml-0 md:ml-64">
      <!-- Top Navbar -->
      <nav class="border-b border-gray-100 bg-white">
        <div class="flex justify-between h-16 items-center px-4">
          <!-- Logo -->
          <Link :href="route('dashboard')">
            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
          </Link>

          <!-- Desktop User Menu -->
          <div class="hidden sm:flex items-center">
            <Dropdown align="right" width="48">
              <template #trigger>
                <button
                  type="button"
                  class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none"
                >
                  {{ $page.props.auth.user.name }}
                  <svg
                    class="ml-2 h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </button>
              </template>
              <template #content>
                <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
              </template>
            </Dropdown>
          </div>

          <!-- Hamburger for mobile -->
          <div class="flex sm:hidden">
            <button
              @click="showingNavigationDropdown = !showingNavigationDropdown"
              class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none"
            >
              <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path
                  :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
                <path
                  :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>

        <!-- Mobile Menu -->
        <div
          :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
          class="sm:hidden bg-white border-t border-gray-200"
        >
          <div class="space-y-1 py-2">
            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</ResponsiveNavLink>
            <ResponsiveNavLink :href="route('apartments.index')" :active="route().current('apartments.index')">Apartments</ResponsiveNavLink>
            <ResponsiveNavLink :href="route('renters.index')" :active="route().current('renters.index')">Renters</ResponsiveNavLink>
            <ResponsiveNavLink :href="route('payments.index')" :active="route().current('payments.index')">Payments</ResponsiveNavLink>
            <ResponsiveNavLink :href="route('sms.index')" :active="route().current('sms.index')">SMS</ResponsiveNavLink>
            <ResponsiveNavLink :href="route('emails.index')" :active="route().current('emails.index')">Emails</ResponsiveNavLink>
          </div>

          <!-- Mobile User Section -->
          <div class="border-t border-gray-200 py-3">
            <div class="px-4">
              <div class="text-base font-medium text-gray-800">{{ $page.props.auth.user.name }}</div>
              <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
            </div>
            <div class="mt-3 space-y-1">
              <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
              <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Header -->
      <header v-if="$slots.header" class="bg-white shadow">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
