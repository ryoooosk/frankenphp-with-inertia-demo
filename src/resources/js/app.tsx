import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import '../css/app.css';
import AppLayout from './Layouts/AppLayout';

createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.glob('./Pages/**/*.tsx', { eager: true });
    const page = pages[`./Pages/${name}.tsx`] as {
      default: { layout?: unknown };
    };
    page.default.layout ??= (children: React.ReactNode) => (
      <AppLayout>{children}</AppLayout>
    );
    return page;
  },
  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />);
  },
});
