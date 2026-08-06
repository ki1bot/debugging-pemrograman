import FlashMessage from "@/Components/FlashMessage";
import PublicFooter from "@/Components/Layout/PublicFooter";
import PublicHeader from "@/Components/Layout/PublicHeader";
import SiteBackdrop from "@/Components/Layout/SiteBackdrop";
import "../../css/site.css";
import { PropsWithChildren } from "react";

export default function PublicLayout({ children }: PropsWithChildren) {
    return (
        <div className="user-site min-h-screen">
            <SiteBackdrop />

            <FlashMessage />

            <PublicHeader />

            <main className="relative z-10">{children}</main>

            <PublicFooter />
        </div>
    );
}
