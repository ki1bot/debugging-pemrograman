import ApplicationLogo from "@/Components/ApplicationLogo";
import { PageProps } from "@/types";
import { Link } from "@inertiajs/react";

type AdminHeaderProps = {
    user: PageProps["auth"]["user"];
    open: boolean;
    toggle: () => void;
};

export default function AdminHeader({ user, open, toggle }: AdminHeaderProps) {
    return (
        <header className="sticky top-0 z-50 border-b-[3px] border-black bg-[#111111] text-white">
            <div className="flex items-center justify-between gap-4 px-4 py-4 sm:px-6">
                <div className="flex items-center gap-4">
                    <ApplicationLogo href={route("admin.dashboard")} />

                    <span className="hidden border-2 border-white bg-[#b7a4ff] px-3 py-1 text-sm font-black text-black md:inline-flex">
                        ADMIN PANEL
                    </span>
                </div>

                <div className="hidden items-center gap-3 lg:flex">
                    <div className="text-right">
                        <p className="text-sm font-black">{user?.name}</p>

                        <p className="text-xs font-bold text-neutral-300">
                            {user?.email}
                        </p>
                    </div>

                    <Link
                        href={route("dashboard")}
                        className="border-2 border-white bg-white px-3 py-2 text-sm font-black text-black"
                    >
                        Lihat Situs
                    </Link>

                    <Link
                        href={route("logout")}
                        method="post"
                        as="button"
                        className="border-2 border-white bg-[#ff6b6b] px-3 py-2 text-sm font-black text-black"
                    >
                        Keluar
                    </Link>
                </div>

                <button
                    type="button"
                    onClick={toggle}
                    className="grid h-11 w-11 place-items-center border-2 border-white bg-[#ffd93d] text-xl font-black text-black lg:hidden"
                    aria-label="Buka navigasi admin"
                >
                    {open ? "×" : "☰"}
                </button>
            </div>
        </header>
    );
}
