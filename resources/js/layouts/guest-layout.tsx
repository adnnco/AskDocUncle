import GuestLayoutTemplate from '@/layouts/guest/layout';

export default function GuestLayout({ children, title, description, ...props }: { children: React.ReactNode; title: string; description: string }) {
    return (
        <GuestLayoutTemplate title={title} description={description} {...props}>
            {children}
        </GuestLayoutTemplate>
    );
}
