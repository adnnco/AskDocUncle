import { useCallback, useState } from 'react';

export type LayoutTab = 'sidebar' | 'header';

const setCookie = (name: string, value: string, days = 365) => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;
    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

export function useLayoutTab() {
    const [layoutTab, setLayoutTab] = useState<LayoutTab>(() => {
        const savedLayoutTab = localStorage.getItem('layoutTab') as LayoutTab | null;
        return savedLayoutTab || 'sidebar';
    });

    const updateLayoutTab = useCallback((mode: LayoutTab) => {
        setLayoutTab(mode);

        // Store in localStorage for client-side persistence...
        localStorage.setItem('layoutTab', mode);

        // Store in cookie for SSR...
        setCookie('layoutTab', mode);

        // Reload the page to apply the layout change...
        window.location.reload();
    }, []);

    return { layoutTab, updateLayoutTab } as const;
}
