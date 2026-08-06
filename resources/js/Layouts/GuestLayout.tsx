import ApplicationLogo from "@/Components/ApplicationLogo";
import FlashMessage from "@/Components/FlashMessage";
import SiteBackdrop from "@/Components/Layout/SiteBackdrop";
import "../../css/site.css";
import { Link } from "@inertiajs/react";
import { ArrowLeft, Sparkles } from "lucide-react";
import { PropsWithChildren } from "react";

export default function GuestLayout({ children }: PropsWithChildren) {
    return (
        <div className="user-site relative grid min-h-screen place-items-center px-4 py-8 sm:px-6 sm:py-12">
            <SiteBackdrop />

            <FlashMessage />

            <main className="relative z-10 w-full max-w-lg">
                <div className="mb-5 flex items-center justify-between gap-4">
                    <ApplicationLogo className="min-w-0" />

                    <Link
                        href={route("home")}
                        className="nb-button nb-button-light shrink-0 px-4 text-sm"
                    >
                        <ArrowLeft className="h-4 w-4" strokeWidth={2.6} />
                        Beranda
                    </Link>
                </div>

                <div className="site-auth-card">{children}</div>

                <p className="mt-6 text-center text-xs font-bold text-[#817989]">
                    Latih kemampuan membaca, menemukan, dan memperbaiki bug.
                </p>
            </main>
        </div>
    );
}
