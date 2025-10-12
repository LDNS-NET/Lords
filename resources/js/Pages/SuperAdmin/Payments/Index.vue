<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import { Banknote } from "lucide-vue-next";
import SuperAdminLayout from "@/Layouts/SuperAdminLayout.vue";
const page = usePage();

const props = computed(() => page.props ?? {});

// Extract specific props
const stats = computed(() => [
    {
        title: "Payments",
        value: `KSh ${Number(
            props.value?.totalPayments || 0
        ).toLocaleString()}`,
        icon: Banknote,
        color: "bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300",
        link: route("superadmin.payments.index"),
    },
]);
</script>

<template>
    <Head title="Payments" />

    <SuperAdminLayout>
        <template #header>
            <h2 class="text-2xl font-semibold leading-tight">Payments</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        v-for="stat in stats"
                        :key="stat.title"
                        class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg"
                    >
                        <div class="p-5">
                            <div class="flex items-center"></div>
                            <stat.icon
                                class="h-6 w-6 text-gray-400 dark:text-gray-500"
                            />
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                >
                                    {{ stat.title }}
                                </p>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ stat.value }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SuperAdminLayout>
</template>
