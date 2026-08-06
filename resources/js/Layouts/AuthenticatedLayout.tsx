import FlashMessage from "@/Components/FlashMessage";
import AuthenticatedHeader from "@/Components/Layout/AuthenticatedHeader";
import PublicFooter from "@/Components/Layout/PublicFooter";
import SiteBackdrop from "@/Components/Layout/SiteBackdrop";
import "../../css/site.css";
import { memo, type PropsWithChildren, type ReactNode } from "react";

type AuthenticatedLayoutProps = PropsWithChildren<{
    header?: ReactNode;
}>;

const MemoizedFlashMessage = memo(FlashMessage);
const MemoizedAuthenticatedHeader = memo(AuthenticatedHeader);
const MemoizedPublicFooter = memo(PublicFooter);
const MemoizedSiteBackdrop = memo(SiteBackdrop);

export default function AuthenticatedLayout({
    header,
    children,
}: AuthenticatedLayoutProps) {
    return (
        <div className="user-site min-h-screen">
            <MemoizedSiteBackdrop />

            <MemoizedFlashMessage />

            <MemoizedAuthenticatedHeader />

            {header && (
                <section className="site-page-header">
                    <div className="mx-auto max-w-7xl px-4 py-9 sm:px-6 sm:py-12 lg:px-8">
                        {header}
                    </div>
                </section>
            )}

            <main className="relative z-10">{children}</main>

            <MemoizedPublicFooter />
        </div>
    );
}
