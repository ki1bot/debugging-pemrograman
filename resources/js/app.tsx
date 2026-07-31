import "../css/app.css";

import { createInertiaApp } from "@inertiajs/react";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createRoot } from "react-dom/client";

const appName = "Rifqi | Debugging Pemrograman";

createInertiaApp({
    title: (title) => {
        const pageTitle = title?.trim();

        if (!pageTitle || pageTitle === appName) {
            return appName;
        }

        return `${pageTitle} | ${appName}`;
    },
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.tsx`,
            import.meta.glob("./Pages/**/*.tsx"),
        ),
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
    progress: {
        color: "#111111",
    },
});
