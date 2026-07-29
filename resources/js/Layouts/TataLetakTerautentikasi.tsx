import FlashMessage from "@/Components/PesanNotifikasi";
import AuthenticatedHeader from "@/Components/Layout/BagianAtasTerautentikasi";
import { PropsWithChildren, ReactNode } from "react";

type AuthenticatedLayoutProps = PropsWithChildren<{
    header?: ReactNode;
}>;

export default function AuthenticatedLayout({
    header,
    children,
}: AuthenticatedLayoutProps) {
    return (
        <div className="min-h-screen">
            <FlashMessage />

            <AuthenticatedHeader />

            {header && (
                <section className="border-b-[3px] border-black bg-[#ffd93d]">
                    <div className="mx-auto max-w-7xl px-4 py-7 sm:px-6 lg:px-8">
                        {header}
                    </div>
                </section>
            )}

            <main>{children}</main>
        </div>
    );
}
