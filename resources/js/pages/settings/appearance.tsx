import { Head } from '@inertiajs/react';

import AppearanceTabs from '@/components/appearance-tabs';
import AppearanceLayoutSwitcher from '@/components/appearance-layout-switcher';

import HeadingSmall from '@/components/heading-small';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: '/settings/appearance',
    },
];

export default function Appearance() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Appearance settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall title="Appearance settings" description="Update your account's appearance settings" />
                    <AppearanceTabs />

                    <HeadingSmall title="App Layout settings" description="Update your app's layout settings" />
                    <AppearanceLayoutSwitcher />
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
