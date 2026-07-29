import FlashMessage from "@/Components/PesanNotifikasi";
import PublicFooter from "@/Components/Layout/BagianBawahPublik";
import PublicHeader from "@/Components/Layout/BagianAtasPublik";
import { PropsWithChildren } from "react";

export default function PublicLayout({ children }: PropsWithChildren) {
    return (
        <div className="min-h-screen">
            <FlashMessage />

            <PublicHeader />

            <main>{children}</main>

            <PublicFooter />
        </div>
    );
}
