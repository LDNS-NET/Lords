import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// ✅ Import Heroicons (outline + solid)
import * as HeroIconsOutline from '@heroicons/vue/24/outline';
import * as HeroIconsSolid from '@heroicons/vue/24/solid';

// ✅ Import Lucide icons (tree-shakable)
import * as LucideIcons from 'lucide-vue-next';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });

        vueApp.use(plugin).use(ZiggyVue);

        // ✅ Register Heroicons globally
        Object.entries(HeroIconsOutline).forEach(([name, component]) => {
            vueApp.component(`${name}Outline`, component);
        });
        Object.entries(HeroIconsSolid).forEach(([name, component]) => {
            vueApp.component(`${name}Solid`, component);
        });

        // ✅ Register Lucide icons safely (skip invalid components)
        Object.entries(LucideIcons).forEach(([name, component]) => {
            if (name && component && typeof component === 'object') {
                vueApp.component(name, component);
            }
        });

        vueApp.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
