import { LayoutTab, useLayoutTab } from '@/hooks/use-appearance-layout';
import { cn } from '@/lib/utils';
import { LucideIcon, PanelLeftIcon, PanelTopIcon } from 'lucide-react';
import { HTMLAttributes } from 'react';

export default function AppearanceLayoutSwitcher({ className = '', ...props }: HTMLAttributes<HTMLDivElement>) {
    const { layoutTab, updateLayoutTab } = useLayoutTab();

    const tabs: { value: LayoutTab; icon: LucideIcon; label: string }[] = [
        { value: 'header', icon: PanelTopIcon, label: 'Header' },
        { value: 'sidebar', icon: PanelLeftIcon, label: 'Sidebar' },
    ];

    return (
        <div className={cn('inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800', className)} {...props}>
            {tabs.map(({ value, icon: Icon, label }) => (
                <button
                    key={value}
                    onClick={() => updateLayoutTab(value)}
                    className={cn(
                        'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
                        layoutTab === value
                            ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                            : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
                    )}
                >
                    <Icon className="-ml-1 h-4 w-4" />
                    <span className="ml-1.5 text-sm">{label}</span>
                </button>
            ))}
        </div>
    );
}
