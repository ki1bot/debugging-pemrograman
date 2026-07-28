import ApplicationLogo from "@/Components/ApplicationLogo";
import FlashMessage from "@/Components/FlashMessage";
import { Link } from "@inertiajs/react";
import { PropsWithChildren } from "react";

export default function GuestLayout({ children }: PropsWithChildren) {
    return (
        <div className="relative grid min-h-screen place-items-center overflow-hidden px-3 py-8 sm:px-6 sm:py-12">
            <FlashMessage />

            <div
                className="absolute -left-24 top-10 h-48 w-48 rotate-12 rounded-2xl border-[3px] border-black bg-[#f8dc4d] shadow-[7px_7px_0_#171717] sm:-left-14 sm:h-56 sm:w-56"
                aria-hidden="true"
            />

            <div
                className="absolute -right-24 bottom-10 h-52 w-52 -rotate-12 rounded-2xl border-[3px] border-black bg-[#ff9eb5] shadow-[7px_7px_0_#171717] sm:-right-14 sm:h-60 sm:w-60"
                aria-hidden="true"
            />

            <div className="relative z-10 w-full max-w-md">
                <div className="mb-6 flex items-center justify-between gap-3 sm:mb-7">
                    <ApplicationLogo href={route("home")} />

                    <Link
                        href={route("home")}
                        className="nb-button nb-button-sm bg-white"
                    >
                        Beranda
                    </Link>
                </div>

                <div className="nb-card bg-white p-5 sm:p-8">{children}</div>
            </div>
        </div>
    );
}
