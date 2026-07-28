import ApplicationLogo from "@/Components/ApplicationLogo";
import FlashMessage from "@/Components/FlashMessage";
import { PageProps } from "@/types";
import { Link, usePage } from "@inertiajs/react";
import { PropsWithChildren, ReactNode } from "react";

type AuthenticatedLayoutProps = PropsWithChildren<{
    header?: ReactNode;
}>;

const navigation = [
    {
        label: "Dashboard",
        routeName: "dashboard",
    },
    {
        label: "Tantangan",
        routeName: "challenges.index",
    },
    {
        label: "Riwayat",
        routeName: "history.index",
    },
    {
        label: "Leaderboard",
        routeName: "leaderboard",
    },
];

export default function AuthenticatedLayout({
    header,
    children,
}: AuthenticatedLayoutProps) {
    const { auth } = usePage<PageProps>().props;
    const user = auth.user;

    return (
        <div className="min-h-screen">
            <FlashMessage />

            <header className="nb-site-header">
                <div className="nb-header-row">
                    <ApplicationLogo href={route("dashboard")} />

                    <div className="flex shrink-0 items-center gap-2">
                        <div className="nb-user-chip hidden max-w-48 md:block">
                            <p className="truncate text-xs font-black uppercase tracking-wide">
                                {user?.name}
                            </p>

                            <p className="truncate text-xs font-bold text-neutral-600">
                                {user?.total_points ?? 0} poin
                            </p>
                        </div>

                        <Link
                            href={route("profile.edit")}
                            className="nb-button nb-button-sm bg-[#8ed8ff] px-3 text-xs sm:px-4 sm:text-sm"
                        >
                            Profil
                        </Link>

                        <Link
                            href={route("logout")}
                            method="post"
                            as="button"
                            className="nb-button nb-button-sm bg-[#ff9eb5] px-3 text-xs sm:px-4 sm:text-sm"
                        >
                            Keluar
                        </Link>
                    </div>
                </div>

                <div className="border-t-2 border-black bg-white">
                    <nav
                        className="mx-auto flex max-w-7xl gap-2 overflow-x-auto px-3 py-3 sm:px-6 lg:justify-center lg:px-8"
                        aria-label="Navigasi pengguna"
                    >
                        {navigation.map((item) => {
                            const active = Boolean(
                                route().current(item.routeName),
                            );

                            return (
                                <Link
                                    key={item.routeName}
                                    href={route(item.routeName)}
                                    className={`nb-nav-link shrink-0 ${
                                        active ? "nb-nav-link-active" : ""
                                    }`}
                                    aria-current={active ? "page" : undefined}
                                >
                                    {item.label}
                                </Link>
                            );
                        })}

                        {user?.role === "admin" && (
                            <Link
                                href={route("admin.dashboard")}
                                className="nb-nav-link shrink-0 bg-[#c3b4ff]"
                            >
                                Admin
                            </Link>
                        )}
                    </nav>
                </div>
            </header>

            {header && (
                <section className="border-b-[3px] border-black bg-[#f8dc4d]">
                    <div className="nb-container py-6 sm:py-8">{header}</div>
                </section>
            )}

            <main>{children}</main>
        </div>
    );
}
