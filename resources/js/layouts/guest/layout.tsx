import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { ChevronLeft } from 'lucide-react';
import { type PropsWithChildren } from 'react';

interface GuestLayoutProps {
    name?: string;
    title?: string;
    description?: string;
}

export default function GuestLayout({ children, title, description }: PropsWithChildren<GuestLayoutProps>) {
    return (
        <div className="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div className="w-full max-w-2xl">
                <div className="flex flex-col gap-8">
                    <div className="flex flex-col items-center gap-4">
                        <div className="flex w-full items-center justify-between">
                            <Button variant="outline" size="sm" className="flex items-center gap-1" onClick={() => window.history.back()}>
                                <ChevronLeft className="h-4 w-4" />
                                Back
                            </Button>

                            <p className="text-muted-foreground text-sm">Last updated: {new Date().toLocaleDateString()}</p>
                        </div>
                        <Separator />
                        <div className="space-y-2 text-center">
                            <h1 className="text-xl font-medium">{title}</h1>
                            <p className="text-muted-foreground text-center text-sm">{description}</p>
                        </div>
                    </div>
                    {children}
                </div>
            </div>
        </div>
    );
}
