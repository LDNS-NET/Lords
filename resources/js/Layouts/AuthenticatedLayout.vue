<script setup>
import { ref, onMounted, watch } from "vue";
import { Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { useTheme } from "@/composables/useTheme";

// ✅ Icons (Lucide + Heroicons)
import {
  LayoutDashboard,
  Home,
  Users,
  Banknote,
  MessageSquare,
  Mail,
  LogOut,
  Settings,
  UserCircle,
  Building2,
  FileText,
} from "lucide-vue-next";
import {
  HomeIcon,
  BuildingOfficeIcon,
  UserGroupIcon,
  DocumentCurrencyDollarIcon,
  ChatBubbleLeftRightIcon,
  EnvelopeIcon,
} from "@heroicons/vue/24/outline";

// Theme composable
const { theme, applyTheme } = useTheme();
const sidebarOpen = ref(false);
const showingNavigationDropdown = ref(false);

onMounted(() => {
  const savedTheme = localStorage.getItem("house_theme");
  if (savedTheme) {
    theme.value = savedTheme;
    applyTheme(savedTheme);
  }
});

watch(theme, (val) => {
  localStorage.setItem("house_theme", val);
  applyTheme(val);
});
</script>

<template>
  <div
    :class="[
      'flex min-h-screen',
      theme === 'dark'
        ? 'bg-gray-900 text-white'
        : 'bg-gradient-to-br from-gray-50 to-gray-100 text-gray-900',
    ]"
  >
    <!-- Sidebar -->
    <aside
      :class="[
        theme === 'dark' ? 'bg-gray-800' : 'bg-white',
        'shadow-lg fixed inset-y-0 left-0 z-30 w-64 transition-transform duration-200 ease-in-out',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        'lg:relative lg:translate-x-0 lg:w-64 lg:flex-shrink-0',
      ]"
    >
      <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200">
        <Link :href="route('dashboard')" class="flex items-center space-x-2">
          <ApplicationLogo class="h-8 w-auto" />
          <span class="font-semibold text-lg">HouseMS</span>
        </Link>
        <button
          @click="sidebarOpen = false"
          class="lg:hidden text-gray-500 hover:text-gray-700"
        >
          <svg
            class="h-6 w-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>

      <!-- Sidebar Links -->
      <div class="p-4 space-y-1">
        <div class="mb-4 px-2 text-gray-500 uppercase tracking-wider text-xs">
        
        <NavLink
          :href="route('dashboard')"
          :active="route().current('dashboard')"
          class="flex items-center p-2"
        >
          <LayoutDashboard class="mr-2 h-4 w-4 text-blue-500" />
          Dashboard
        </NavLink>
          </div>

          <div class="mb-4 px-2 text-gray-500 uppercase tracking-wider text-xs">
        <NavLink
          :href="route('apartments.index')"
          :active="route().current('apartments.index')"
          class="flex items-center p-2"
        >
          <Building2 class="mr-2 h-4 w-4 text-indigo-500" />
          Apartments
        </NavLink>
          </div>

          <div class="mb-4 px-2 text-gray-500 uppercase tracking-wider text-xs">
        <NavLink
          :href="route('renters.index')"
          :active="route().current('renters.index')"
          class="flex items-center p-2"
        >
          <Users class="mr-2 h-4 w-4 text-emerald-500" />
          Renters
        </NavLink>
        </div>


        <div class="mb-4 px-2 text-gray-500 uppercase tracking-wider text-xs">
        <NavLink
          :href="route('payments.index')"
          :active="route().current('payments.index')"
          class="flex items-center p-2"
        >
          <Banknote class="mr-2 h-4 w-4 text-yellow-500" />
          Payments
        </NavLink>
        </div>


        <div class="mb-4 px-2 text-gray-500 uppercase tracking-wider text-xs">
        <NavLink
          :href="route('sms.index')"
          :active="route().current('sms.index')"
          class="flex items-center p-2"
        >
          <MessageSquare class="mr-2 h-4 w-4 text-purple-500" />
          SMS
        </NavLink>
        </div>


        <div class="mb-4 px-2 text-gray-500 uppercase tracking-wider text-xs">
        <NavLink
          :href="route('emails.index')"
          :active="route().current('emails.index')"
          class="flex items-center p-2"
        >
          <Mail class="mr-2 h-4 w-4 text-pink-500" />
          Emails
        </NavLink>
        </div>
        
      </div>
    </aside>

    <!-- Overlay (mobile) -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 bg-black opacity-50 z-20 lg:hidden"
    ></div>

    <!-- Main Section -->
    <div class="flex flex-col flex-1">
      <!-- Top Navbar -->
      <nav
        :class="[
          theme === 'dark'
            ? 'bg-gray-900 border-b border-gray-700 text-white'
            : 'bg-white border-b border-gray-200 text-gray-900',
          'flex justify-between items-center px-4 py-3 shadow-sm',
        ]"
      >
        <!-- Hamburger -->
        <button
          @click="sidebarOpen = true"
          class="lg:hidden text-gray-600 focus:outline-none"
        >
          <svg
            class="h-6 w-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"
            />
          </svg>
        </button>

        <div class="flex items-center space-x-4 ml-auto">
          <!-- Theme toggle -->
          <button
            @click="theme = theme === 'dark' ? 'light' : 'dark'"
            class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800"
          >
            <svg
              v-if="theme === 'dark'"
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 3v1m0 16v1m8.485-8.485h1M3.515 12.515h1m12.02 6.364l.707.707M6.343 6.343l.707.707m12.02-6.364l.707.707M6.343 17.657l.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            <svg
              v-else
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21.752 15.002A9.718 9.718 0 0112 21a9.718 9.718 0 01-9.752-5.998A9.718 9.718 0 0112 3a9.718 9.718 0 019.752 5.998z"
              />
            </svg>
          </button>

          <!-- User Dropdown -->
          <Dropdown align="right" width="48">
            <template #trigger>
              <button
                type="button"
                class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-200"
              >
                <UserCircle class="h-5 w-5 mr-1 text-gray-500" />
                {{ $page.props.auth.user.name }}
                <svg
                  class="ml-2 h-4 w-4"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    fill-rule="evenodd"
                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.96a.75.75 0 111.08 1.04l-4.24 4.52a.75.75 0 01-1.08 0l-4.24-4.52a.75.75 0 01.02-1.06z"
                    clip-rule="evenodd"
                  />
                </svg>
              </button>
            </template>

            <template #content>
              <DropdownLink :href="route('profile.edit')">
                <Settings class="h-4 w-4 mr-2" />
                Profile
              </DropdownLink>
              <DropdownLink
                :href="route('logout')"
                method="post"
                as="button"
                class="flex items-center"
              >
                <LogOut class="h-4 w-4 mr-2 text-red-600" />
                Log Out
              </DropdownLink>
            </template>
          </Dropdown>
        </div>
      </nav>

      <!-- Header -->
      <header
        v-if="$slots.header"
        :class="[
          theme === 'dark' ? 'bg-gray-900 text-white shadow' : 'bg-white shadow',
        ]"
      >
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Main Content -->
      <main class="p-4">
        <slot />
      </main>
    </div>
  </div>
</template>
