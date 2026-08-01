import ApplicationLogo from "@/Components/ApplicationLogo";
import { authenticatedNavigation } from "@/Components/Layout/navigation";
import { PageProps } from "@/types";
import { Link, usePage } from "@inertiajs/react";
import {
    ArrowRight,
    Clock3,
    Compass,
    LayoutDashboard,
    LogOut,
    Menu,
    ShieldCheck,
    Trophy,
    UserRound,
    X,
} from "lucide-react";
import type { LucideIcon } from "lucide-react";
import { useState } from "react";

const navigationIcons: Record<string, LucideIcon> = {
    dashboard: LayoutDashboard,
    "challenges.index": Compass,
    "history.index": Clock3,
    leaderboard: Trophy,
};

export default function AuthenticatedHeader() {
    const { auth } = usePage<PageProps>().props;
    const user = auth.user;
    const [open, setOpen] = useState(false);

    return (
        <header className="sticky top-0 z-50 border-b border-[#21162f]/10 bg-[#fbfaff]/85 backdrop-blur-xl">
            <div className="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3.5 sm:gap-4 sm:px-6 sm:py-4 lg:px-8">
                <ApplicationLogo className="min-w-0" />

                <nav className="hidden items-center rounded-2xl border border-[#21162f]/10 bg-white/75 p-1.5 shadow-sm xl:flex">
                    {authenticatedNavigation.map((item) => {
                        const Icon =
                            navigationIcons[item.routeName] ?? ArrowRight;
                        const active = route().current(item.routeName);

                        return (
                            <Link
                                key={item.routeName}
                                href={route(item.routeName)}
                                className={`inline-flex items-center gap-2 rounded-xl px-3.5 py-2.5 text-sm font-extrabold transition ${
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

                    {user?.role === "admin" && (
                        <Link
                            href={route("admin.dashboard")}
                            className="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#9c88f7] to-[#f56eb3] px-3.5 py-2.5 text-sm font-black text-white shadow-md"
                        >
                            <ShieldCheck
                                className="h-4 w-4"
                                strokeWidth={2.6}
                            />
                            Admin
                        </Link>
                    )}
                </nav>

                <div className="hidden items-center gap-3 xl:flex">
                    <div className="rounded-xl border border-[#21162f]/10 bg-white px-4 py-2 shadow-sm">
                        <p className="max-w-36 truncate text-sm font-black text-[#21162f]">
                            {user?.name}
                        </p>

                        <p className="text-xs font-bold text-[#777080]">
                            {user?.total_points ?? 0} poin
                        </p>
                    </div>

                    <Link
                        href={route("profile.edit")}
                        className="nb-button nb-button-secondary text-sm"
                    >
                        <UserRound className="h-4 w-4" strokeWidth={2.7} />
                        Profil
                    </Link>

                    <Link
                        href={route("logout")}
                        method="post"
                        as="button"
                        className="nb-button bg-[#ffd2df] text-sm"
                    >
                        <LogOut className="h-4 w-4" strokeWidth={2.7} />
                        Keluar
                    </Link>
                </div>

                <button
                    type="button"
                    onClick={() => setOpen((value) => !value)}
                    className="nb-button nb-button-primary h-11 min-h-0 w-11 shrink-0 p-0 xl:hidden"
                    aria-label={open ? "Tutup navigasi" : "Buka navigasi"}
                    aria-expanded={open}
                    aria-controls="authenticated-mobile-navigation"
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
                    id="authenticated-mobile-navigation"
                    className="max-h-[calc(100dvh-68px)] overflow-y-auto border-t border-[#21162f]/10 bg-[#fbfaff]/95 px-4 py-4 backdrop-blur-xl xl:hidden"
                >
                    <div className="mx-auto grid max-w-7xl gap-2">
                        <div className="mb-2 rounded-2xl border border-[#21162f]/10 bg-white p-4 shadow-sm">
                            <div className="flex min-w-0 items-center gap-3">
                                <span className="neo-icon-box h-11 w-11 shrink-0 bg-gradient-to-br from-[#8dd4fa] to-[#9c88f7]">
                                    <UserRound
                                        className="h-5 w-5"
                                        strokeWidth={2.6}
                                    />
                                </span>

                                <div className="min-w-0">
                                    <p className="truncate font-black text-[#21162f]">
                                        {user?.name}
                                    </p>

                                    <p className="truncate text-sm font-bold text-[#777080]">
                                        {user?.email}
                                    </p>

                                    <p className="text-xs font-black text-[#9c5fe2]">
                                        {user?.total_points ?? 0} poin
                                    </p>
                                </div>
                            </div>
                        </div>

                        {authenticatedNavigation.map((item) => {
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

                        {user?.role === "admin" && (
                            <Link
                                href={route("admin.dashboard")}
                                onClick={() => setOpen(false)}
                                className="flex items-center gap-3 rounded-xl border border-[#21162f] bg-gradient-to-r from-[#9c88f7] to-[#f56eb3] px-4 py-3 font-black text-white"
                            >
                                <ShieldCheck
                                    className="h-5 w-5"
                                    strokeWidth={2.6}
                                />
                                Dashboard Admin
                            </Link>
                        )}

                        <div className="mt-2 grid grid-cols-1 gap-3 min-[380px]:grid-cols-2">
                            <Link
                                href={route("profile.edit")}
                                onClick={() => setOpen(false)}
                                className="nb-button nb-button-secondary w-full"
                            >
                                <UserRound
                                    className="h-4 w-4"
                                    strokeWidth={2.7}
                                />
                                Profil
                            </Link>

                            <Link
                                href={route("logout")}
                                method="post"
                                as="button"
                                className="nb-button w-full bg-[#ffd2df]"
                            >
                                <LogOut className="h-4 w-4" strokeWidth={2.7} />
                                Keluar
                            </Link>
                        </div>
                    </div>
                </div>
            )}
        </header>
    );
}
