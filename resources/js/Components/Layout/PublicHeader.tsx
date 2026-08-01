import ApplicationLogo from "@/Components/ApplicationLogo";
import { publicNavigation } from "@/Components/Layout/navigation";
import { PageProps } from "@/types";
import { Link, usePage } from "@inertiajs/react";
import {
    ArrowRight,
    Compass,
    House,
    Info,
    LayoutDashboard,
    LogIn,
    Menu,
    Trophy,
    UserPlus,
    X,
} from "lucide-react";
import type { LucideIcon } from "lucide-react";
import { useState } from "react";

const navigationIcons: Record<string, LucideIcon> = {
    home: House,
    "challenges.index": Compass,
    leaderboard: Trophy,
    about: Info,
};

export default function PublicHeader() {
    const { auth } = usePage<PageProps>().props;
    const [open, setOpen] = useState(false);

    return (
        <header className="sticky top-0 z-50 border-b border-[#21162f]/10 bg-[#fbfaff]/85 backdrop-blur-xl">
            <div className="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3.5 sm:gap-5 sm:px-6 sm:py-4 lg:px-8">
                <ApplicationLogo className="min-w-0" />

                <nav className="hidden items-center rounded-2xl border border-[#21162f]/10 bg-white/75 p-1.5 shadow-sm lg:flex">
                    {publicNavigation.map((item) => {
                        const Icon =
                            navigationIcons[item.routeName] ?? ArrowRight;
                        const active = route().current(item.routeName);

                        return (
                            <Link
                                key={item.routeName}
                                href={route(item.routeName)}
                                className={`inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-extrabold transition ${
                                    active
                                        ? "bg-[#21162f] text-white shadow-md"
                                        : "text-[#665f73] hover:bg-[#f2eff8] hover:text-[#21162f]"
                                }`}
                            >
                                <Icon className="h-4 w-4" strokeWidth={2.5} />
                                {item.label}
                            </Link>
                        );
                    })}
                </nav>

                <div className="hidden items-center gap-3 lg:flex">
                    {auth.user ? (
                        <Link
                            href={route("dashboard")}
                            className="nb-button nb-button-primary px-5"
                        >
                            <LayoutDashboard
                                className="h-4 w-4"
                                strokeWidth={2.7}
                            />
                            Dashboard
                        </Link>
                    ) : (
                        <>
                            <Link
                                href={route("login")}
                                className="nb-button nb-button-light px-5"
                            >
                                <LogIn className="h-4 w-4" strokeWidth={2.8} />
                                Masuk
                            </Link>

                            <Link
                                href={route("register")}
                                className="nb-button nb-button-primary px-5"
                            >
                                <UserPlus
                                    className="h-4 w-4"
                                    strokeWidth={2.7}
                                />
                                Daftar
                            </Link>
                        </>
                    )}
                </div>

                <button
                    type="button"
                    onClick={() => setOpen((value) => !value)}
                    className="nb-button nb-button-primary h-11 min-h-0 w-11 shrink-0 p-0 lg:hidden"
                    aria-label={open ? "Tutup navigasi" : "Buka navigasi"}
                    aria-expanded={open}
                    aria-controls="public-mobile-navigation"
                >
                    {open ? (
                        <X className="h-5 w-5" strokeWidth={2.8} />
                    ) : (
                        <Menu className="h-5 w-5" strokeWidth={2.8} />
                    )}
                </button>
            </div>

            {open && (
                <div
                    id="public-mobile-navigation"
                    className="max-h-[calc(100dvh-68px)] overflow-y-auto border-t border-[#21162f]/10 bg-[#fbfaff]/95 px-4 py-4 backdrop-blur-xl lg:hidden"
                >
                    <div className="mx-auto grid max-w-7xl gap-2">
                        {publicNavigation.map((item) => {
                            const Icon =
                                navigationIcons[item.routeName] ?? ArrowRight;
                            const active = route().current(item.routeName);

                            return (
                                <Link
                                    key={item.routeName}
                                    href={route(item.routeName)}
                                    onClick={() => setOpen(false)}
                                    className={`flex items-center gap-3 rounded-xl border px-4 py-3 font-black transition ${
                                        active
                                            ? "border-[#21162f] bg-[#21162f] text-white"
                                            : "border-[#21162f]/10 bg-white text-[#21162f]"
                                    }`}
                                >
                                    <Icon
                                        className="h-5 w-5"
                                        strokeWidth={2.5}
                                    />
                                    {item.label}
                                </Link>
                            );
                        })}

                        {auth.user ? (
                            <Link
                                href={route("dashboard")}
                                onClick={() => setOpen(false)}
                                className="nb-button nb-button-primary mt-2 w-full"
                            >
                                <LayoutDashboard
                                    className="h-5 w-5"
                                    strokeWidth={2.7}
                                />
                                Dashboard
                            </Link>
                        ) : (
                            <div className="mt-2 grid grid-cols-1 gap-3 min-[380px]:grid-cols-2">
                                <Link
                                    href={route("login")}
                                    onClick={() => setOpen(false)}
                                    className="nb-button nb-button-light w-full"
                                >
                                    <LogIn
                                        className="h-4 w-4"
                                        strokeWidth={2.8}
                                    />
                                    Masuk
                                </Link>

                                <Link
                                    href={route("register")}
                                    onClick={() => setOpen(false)}
                                    className="nb-button nb-button-primary w-full"
                                >
                                    <UserPlus
                                        className="h-4 w-4"
                                        strokeWidth={2.7}
                                    />
                                    Daftar
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            )}
        </header>
    );
}
