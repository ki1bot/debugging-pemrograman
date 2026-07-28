import ApplicationLogo from "@/Components/ApplicationLogo";
import FlashMessage from "@/Components/FlashMessage";
import { PageProps } from "@/types";
import { Link, usePage } from "@inertiajs/react";
import { PropsWithChildren } from "react";

const navigation = [
    {
        label: "Beranda",
        routeName: "home",
    },
    {
        label: "Tantangan",
        routeName: "challenges.index",
    },
    {
        label: "Leaderboard",
        routeName: "leaderboard",
    },
    {
        label: "Tentang",
        routeName: "about",
    },
];

export default function PublicLayout({ children }: PropsWithChildren) {
    const { auth } = usePage<PageProps>().props;

    return (
        <div className="min-h-screen">
            <FlashMessage />

            <header className="nb-site-header">
                <div className="nb-header-row">
                    <ApplicationLogo href={route("home")} />

                    <div className="flex shrink-0 items-center gap-2 sm:gap-3">
                        {auth.user ? (
                            <Link
                                href={route("dashboard")}
                                className="nb-button nb-button-sm bg-[#ff7468] px-3 text-xs sm:px-4 sm:text-sm"
                            >
                                Dashboard
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route("login")}
                                    className="nb-button nb-button-sm bg-white px-3 text-xs sm:px-4 sm:text-sm"
                                >
                                    Masuk
                                </Link>

                                <Link
                                    href={route("register")}
                                    className="nb-button nb-button-sm bg-[#ff7468] px-3 text-xs sm:px-4 sm:text-sm"
                                >
                                    Daftar
                                </Link>
                            </>
                        )}
                    </div>
                </div>

                <div className="border-t-2 border-black bg-white">
                    <nav
                        className="mx-auto flex max-w-7xl gap-2 overflow-x-auto px-3 py-3 sm:px-6 lg:justify-center lg:px-8"
                        aria-label="Navigasi utama"
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
                    </nav>
                </div>
            </header>

            <main>{children}</main>

            <footer className="nb-footer">
                <div className="nb-container grid gap-8 py-10 md:grid-cols-[minmax(0,1fr)_auto] md:items-start">
                    <div>
                        <p className="text-2xl font-black tracking-[-0.05em]">
                            BUG
                            <span className="text-[#f8dc4d]">HUNT</span>
                        </p>

                        <p className="mt-3 max-w-xl font-semibold leading-7 text-neutral-300">
                            Belajar debugging dengan membaca, memperbaiki, dan
                            menjelaskan kesalahan pada kode secara terstruktur.
                        </p>
                    </div>

                    <nav
                        className="flex flex-wrap gap-2 md:max-w-md md:justify-end"
                        aria-label="Navigasi footer"
                    >
                        {navigation.map((item) => (
                            <Link
                                key={item.routeName}
                                href={route(item.routeName)}
                                className="nb-footer-link"
                            >
                                {item.label}
                            </Link>
                        ))}
                    </nav>
                </div>

                <div className="border-t-2 border-white/25 px-4 py-4 text-center text-sm font-bold text-neutral-300">
                    BugHunt — Platform Pembelajaran Debugging Pemrograman
                </div>
            </footer>
        </div>
    );
}
