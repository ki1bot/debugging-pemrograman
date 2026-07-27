import ApplicationLogo from "@/Components/ApplicationLogo";
import FlashMessage from "@/Components/FlashMessage";
import { Link } from "@inertiajs/react";
import { PropsWithChildren } from "react";

export default function GuestLayout({ children }: PropsWithChildren) {
    return (
        <div className="relative grid min-h-screen place-items-center overflow-hidden px-4 py-10">
            <FlashMessage />

            <div className="absolute -left-16 top-12 h-52 w-52 rotate-12 border-[3px] border-black bg-[#ffd93d] shadow-[8px_8px_0_#111]" />

            <div className="absolute -right-16 bottom-12 h-56 w-56 -rotate-12 border-[3px] border-black bg-[#ff8fa3] shadow-[8px_8px_0_#111]" />

            <div className="relative z-10 w-full max-w-md">
                <div className="mb-7 flex items-center justify-between gap-4">
                    <ApplicationLogo />

                    <Link
                        href={route("home")}
                        className="border-2 border-black bg-white px-3 py-2 text-sm font-black shadow-[3px_3px_0_#111]"
                    >
                        Beranda
                    </Link>
                </div>

                <div className="nb-card bg-white p-6 sm:p-8">{children}</div>
            </div>
        </div>
    );
}
