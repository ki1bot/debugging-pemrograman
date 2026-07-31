import FlashMessage from "@/Components/FlashMessage";
import AdminHeader from "@/Components/Layout/AdminHeader";
import AdminSidebar from "@/Components/Layout/AdminSidebar";
import { PageProps } from "@/types";
import { usePage } from "@inertiajs/react";
import { PropsWithChildren, useState } from "react";

type AdminLayoutProps = PropsWithChildren<{
    title: string;
    description?: string;
}>;

export default function AdminLayout({
    title,
    description,
    children,
}: AdminLayoutProps) {
    const { auth } = usePage<PageProps>().props;
    const [open, setOpen] = useState(false);

    return (
        <div className="min-h-screen bg-[#f5f0ff]">
            <FlashMessage />

            <AdminHeader
                user={auth.user}
                open={open}
                toggle={() => setOpen((value) => !value)}
            />

            <div className="mx-auto grid max-w-[1600px] lg:grid-cols-[260px_minmax(0,1fr)]">
                <AdminSidebar open={open} close={() => setOpen(false)} />

                <main className="min-w-0 p-4 sm:p-6 lg:p-8">
                    <div className="mb-8 border-[3px] border-black bg-white p-5 shadow-[6px_6px_0_#111]">
                        <p className="text-xs font-black uppercase tracking-[0.18em] text-neutral-600">
                            Administrasi Debugging Pemrograman
                        </p>

                        <h1 className="mt-2 text-3xl font-black tracking-[-0.05em] sm:text-4xl">
                            {title}
                        </h1>

                        {description && (
                            <p className="mt-3 max-w-3xl font-semibold leading-7 text-neutral-700">
                                {description}
                            </p>
                        )}
                    </div>

                    {children}
                </main>
            </div>
        </div>
    );
}
