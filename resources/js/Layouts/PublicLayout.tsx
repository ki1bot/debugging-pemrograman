import FlashMessage from "@/Components/FlashMessage";
import PublicFooter from "@/Components/Layout/PublicFooter";
import PublicHeader from "@/Components/Layout/PublicHeader";
import SiteBackdrop from "@/Components/Layout/SiteBackdrop";
import "../../css/site.css";
import { memo, type PropsWithChildren } from "react";

const MemoizedFlashMessage = memo(FlashMessage);
const MemoizedPublicFooter = memo(PublicFooter);
const MemoizedPublicHeader = memo(PublicHeader);
const MemoizedSiteBackdrop = memo(SiteBackdrop);

export default function PublicLayout({ children }: PropsWithChildren) {
    return (
        <div className="user-site min-h-screen">
            <MemoizedSiteBackdrop />

            <MemoizedFlashMessage />

            <MemoizedPublicHeader />

            <main className="relative z-10">{children}</main>

            <MemoizedPublicFooter />
        </div>
    );
}
