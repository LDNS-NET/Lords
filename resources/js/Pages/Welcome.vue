<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <img
            id="background"
            class="absolute -left-20 top-0 max-w-[877px]"
            src="https://laravel.com/assets/img/welcome/background.svg"
        />
        <div
            class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header
                    class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3"
                >
                    <div class="flex lg:col-start-2 lg:justify-center">
                        <ApplicationLogo class="h-20 w-auto"/>
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-5 py-5 font-extrabold text-black ring-1 ring-transparent transition hover:text-blue-400 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            <PrimaryButton
                                class="w-full h-full"
                            >
                                Dashboard
                            </PrimaryButton>
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-blue-400 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="relative mt-6">
                    <!-- HERO SECTION -->
                    <section
                        class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#1f1f1f] via-[#2a2a2a] to-[#111111] p-10 shadow-xl"
                    >
                        <div
                            class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1920&q=80')] bg-cover bg-center opacity-20"
                        ></div>

                        <div
                            class="relative z-10 text-center lg:text-left lg:max-w-2xl mx-auto lg:mx-0"
                        >
                            <h1
                                class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight"
                            >
                                Smart
                                <span class="text-[#FF2D20]"
                                    >House Management System</span
                                >
                            </h1>

                            <p
                                class="text-gray-300 text-lg mb-6 leading-relaxed"
                            >
                                Streamline how you manage your properties,
                                tenants, and rent payments — all from one
                                powerful dashboard designed for landlords,
                                property agents, and housing companies across
                                <strong>East Africa and beyond.</strong>
                            </p>

                            <div
                                class="flex justify-center lg:justify-start gap-4"
                            >
                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="px-6 py-3 bg-[#FF2D20] text-white font-semibold rounded-xl shadow-lg hover:bg-blue-400 transition-all duration-300"
                                >
                                    Get Started
                                </Link>

                                <Link
                                    :href="route('login')"
                                    class="px-6 py-3 border border-[#FF2D20] text-[#FF2D20] font-semibold rounded-xl hover:bg-blue-400 hover:text-white transition-all duration-300"
                                >
                                    Log In
                                </Link>
                            </div>
                        </div>
                    </section>

                    <!-- ABOUT SECTION -->
                    <section
                        class="grid gap-10 lg:grid-cols-2 lg:gap-16 mt-16 items-center"
                    >
                        <div
                            class="rounded-2xl bg-white dark:bg-zinc-900 p-8 shadow-xl hover:shadow-2xl transition-all duration-300"
                        >
                            <h2
                                class="text-3xl font-bold mb-4 text-black dark:text-white"
                            >
                                About the System
                            </h2>
                            <p
                                class="text-base text-black/80 dark:text-white/80 leading-relaxed mb-4"
                            >
                                This modern and scalable
                                <strong>House Management System</strong> allows
                                property owners to record and track buildings
                                and rental units, manage tenant information, and
                                handle payments effortlessly. Landlords and
                                agents can monitor revenue, generate reports,
                                and communicate seamlessly with tenants through
                                SMS and email.
                            </p>

                            <p
                                class="text-base text-black/80 dark:text-white/80 leading-relaxed mb-4"
                            >
                                The platform is optimized for both desktop and
                                mobile, supports
                                <strong>mobile money integrations</strong>, and
                                provides role-based access to ensure secure and
                                organized operations.
                            </p>

                            <p
                                class="text-base text-black/80 dark:text-white/80 leading-relaxed"
                            >
                                Built for the evolving real estate market across
                                Africa, it simplifies management and enhances
                                transparency for all stakeholders.
                            </p>
                        </div>

                        <div
                            class="relative rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300"
                        >
                            <img
                                src="https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1920&q=80"
                                alt="House Management Dashboard"
                                class="w-full h-full object-cover"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"
                            ></div>
                            <h3
                                class="absolute bottom-4 left-6 text-white text-2xl font-semibold drop-shadow-lg"
                            >
                                Manage your properties efficiently
                            </h3>
                        </div>
                    </section>

                    <!-- FEATURES GRID -->
                    <section class="mt-20">
                        <h2
                            class="text-center text-3xl font-bold mb-12 text-black dark:text-white"
                        >
                            Core Features
                        </h2>

                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                            <div
                                v-for="(feature, index) in [
                                    {
                                        icon: '🏠',
                                        title: 'Property Management',
                                        text: 'Record and track multiple buildings and units easily.',
                                    },
                                    {
                                        icon: '👥',
                                        title: 'Tenant Records',
                                        text: 'Register and manage tenants with detailed profiles.',
                                    },
                                    {
                                        icon: '💳',
                                        title: 'Online Payments',
                                        text: 'Accept mobile money and online payments seamlessly.',
                                    },
                                    {
                                        icon: '📊',
                                        title: 'Rent Reports',
                                        text: 'Get real-time analytics on rent collection and occupancy.',
                                    },
                                    {
                                        icon: '📱',
                                        title: 'Bulk SMS',
                                        text: 'Send rent reminders and updates via integrated SMS gateway.',
                                    },
                                    {
                                        icon: '📧',
                                        title: 'Email Alerts',
                                        text: 'Keep tenants informed with professional email notifications.',
                                    },
                                    {
                                        icon: '🔐',
                                        title: 'Role-Based Access',
                                        text: 'Secure access for landlords, agents, and admins.',
                                    },
                                    {
                                        icon: '🌍',
                                        title: 'Regional Support',
                                        text: 'Built for East Africa, expanding across regions soon.',
                                    },
                                ]"
                                :key="index"
                                class="group rounded-2xl bg-white dark:bg-zinc-900 p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-zinc-800"
                            >
                                <div
                                    class="text-4xl mb-4 transition-transform group-hover:scale-110"
                                >
                                    {{ feature.icon }}
                                </div>
                                <h3
                                    class="text-xl font-semibold mb-2 text-black dark:text-white"
                                >
                                    {{ feature.title }}
                                </h3>
                                <p class="text-black/70 dark:text-white/70">
                                    {{ feature.text }}
                                </p>
                            </div>
                        </div>

                        <p
                            class="mt-12 text-center text-sm text-black/60 dark:text-white/60"
                        >
                            Built using <strong>Laravel</strong> +
                            <strong>Vue 3</strong> +
                            <strong>Inertia.js</strong> — fast, secure, and
                            reliable.
                        </p>
                    </section>
                </main>

                <footer
                    class="py-16 text-center text-sm text-black dark:text-white/70"
                >
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </footer>
            </div>
        </div>
    </div>
</template>
