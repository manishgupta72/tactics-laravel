import { createInertiaApp } from '@inertiajs/inertia-react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { InertiaProgress } from '@inertiajs/progress';
import ReactDOM from 'react-dom';

createInertiaApp({
    resolve: name =>
        resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({ el, App, props }) {
        ReactDOM.render(<App {...props} />, el);
    },
});

InertiaProgress.init();
