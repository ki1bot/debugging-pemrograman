import FlashMessage from "@/Components/FlashMessage";
import PublicFooter from "@/Components/Layout/PublicFooter";
import PublicHeader from "@/Components/Layout/PublicHeader";
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
