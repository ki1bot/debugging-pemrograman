import ApplicationLogo from "@/Components/ApplicationLogo";
import FlashMessage from "@/Components/FlashMessage";
import { PageProps } from "@/types";
import { Link, usePage } from "@inertiajs/react";
import { PropsWithChildren, useState } from "react";

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
    const [open, setOpen] = useState(false);

    return (
        <div className="min-h-screen">
            <FlashMessage />

            <header className="sticky top-0 z-50 border-b-[3px] border-black bg-[#fff7e6]">
                <div className="mx-auto flex max-w-7xl items-center justify-between gap-5 px-4 py-4 sm:px-6 lg:px-8">
                    <ApplicationLogo />

                    <nav className="hidden items-center gap-2 lg:flex">
                        {navigation.map((item) => (
                            <Link
                                key={item.routeName}
                                href={route(item.routeName)}
                                className={`border-2 border-black px-3 py-2 text-sm font-black shadow-[2px_2px_0_#111] ${
                                    route().current(item.routeName)
                                        ? "bg-[#ffd93d]"
                                        : "bg-white hover:bg-[#fff1a8]"
                                }`}
                            >
                                {item.label}
                            </Link>
                        ))}
                    </nav>

                    <div className="hidden items-center gap-3 lg:flex">
                        {auth.user ? (
                            <Link
                                href={route("dashboard")}
                                className="nb-button bg-[#ff6b6b]"
                            >
                                Dashboard
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route("login")}
                                    className="nb-button bg-white"
                                >
                                    Masuk
                                </Link>

                                <Link
                                    href={route("register")}
                                    className="nb-button bg-[#ff6b6b]"
                                >
                                    Daftar
                                </Link>
                            </>
                        )}
                    </div>

                    <button
                        type="button"
                        onClick={() => setOpen((value) => !value)}
                        className="grid h-11 w-11 place-items-center border-[3px] border-black bg-[#ffd93d] text-xl font-black shadow-[3px_3px_0_#111] lg:hidden"
                        aria-label="Buka navigasi"
                    >
                        {open ? "×" : "☰"}
                    </button>
                </div>

                {open && (
                    <div className="border-t-[3px] border-black bg-white px-4 py-4 lg:hidden">
                        <div className="mx-auto grid max-w-7xl gap-2">
                            {navigation.map((item) => (
                                <Link
                                    key={item.routeName}
                                    href={route(item.routeName)}
                                    onClick={() => setOpen(false)}
                                    className={`border-2 border-black px-4 py-3 font-black ${
                                        route().current(item.routeName)
                                            ? "bg-[#ffd93d]"
                                            : "bg-white"
                                    }`}
                                >
                                    {item.label}
                                </Link>
                            ))}

                            {auth.user ? (
                                <Link
                                    href={route("dashboard")}
                                    className="border-2 border-black bg-[#ff6b6b] px-4 py-3 font-black"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <div className="grid grid-cols-2 gap-2">
                                    <Link
                                        href={route("login")}
                                        className="border-2 border-black bg-white px-4 py-3 text-center font-black"
                                    >
                                        Masuk
                                    </Link>

                                    <Link
                                        href={route("register")}
                                        className="border-2 border-black bg-[#ff6b6b] px-4 py-3 text-center font-black"
                                    >
                                        Daftar
                                    </Link>
                                </div>
                            )}
                        </div>
                    </div>
                )}
            </header>

            <main>{children}</main>

            <footer className="mt-20 border-t-[3px] border-black bg-[#111111] text-white">
                <div className="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-2 lg:px-8">
                    <div>
                        <p className="text-2xl font-black tracking-[-0.05em]">
                            BUG
                            <span className="text-[#ffd93d]">HUNT</span>
                        </p>

                        <p className="mt-3 max-w-lg font-semibold leading-7 text-neutral-300">
                            Belajar debugging dengan membaca, memperbaiki, dan
                            menjelaskan kesalahan pada kode secara terstruktur.
                        </p>
                    </div>

                    <div className="flex flex-wrap items-start gap-3 md:justify-end">
                        {navigation.map((item) => (
                            <Link
                                key={item.routeName}
                                href={route(item.routeName)}
                                className="border-2 border-white px-3 py-2 text-sm font-black hover:bg-white hover:text-black"
                            >
                                {item.label}
                            </Link>
                        ))}
                    </div>
                </div>

                <div className="border-t-2 border-white/30 px-4 py-4 text-center text-sm font-bold text-neutral-300">
                    BugHunt — Platform Pembelajaran Debugging Pemrograman
                </div>
            </footer>
        </div>
    );
}
