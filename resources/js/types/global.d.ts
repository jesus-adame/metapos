import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import { route as ziggyRoute } from 'ziggy-js';
import { PageProps as AppPageProps } from './';
import PDFObjectPlugin from 'pdfobject-vue';

declare global {
    interface Window {
        axios: AxiosInstance;
    }

    var route: typeof ziggyRoute;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {
    }
}

declare module 'pdfobject-vue' {
    const PDFObjectPlugin: any;
    export default PDFObjectPlugin;
}

declare module 'mousetrap' {
    interface MousetrapStatic {
        bindGlobal(keys: string | Array, callback: (event: Event) => void): void;
    }
}
