import ApplicationLogo from "@/Components/ApplicationLogo";
import FlashMessage from "@/Components/FlashMessage";
import { PageProps } from "@/types";
import { Link, usePage } from "@inertiajs/react";
import { PropsWithChildren, useState } from "react";

type AdminLayoutProps = PropsWithChildren<{
    title: string;
    description?: string;
}>;

const navigation = [
    {
        label: "Ringkasan",
        routeName: "admin.dashboard",
        pattern: "admin.dashboard",
    },
    {
        label: "Tantangan",
        routeName: "admin.challenges.index",
        pattern: "admin.challenges.*",
    },
    {
        label: "Kategori",
        routeName: "admin.categories.index",
        pattern: "admin.categories.*",
    },
    {
        label: "Kesulitan",
        routeName: "admin.difficulties.index",
        pattern: "admin.difficulties.*",
    },
    {
        label: "Pengguna",
        routeName: "admin.users.index",
        pattern: "admin.users.*",
    },
    {
        label: "Submission",
        routeName: "admin.submissions.index",
        pattern: "admin.submissions.*",
    },
];

export default function AdminLayout({
    title,
    description,
    children,
}: AdminLayoutProps) {
    const { auth } = usePage<PageProps>().props;
    const [open, setOpen] = useState(false);

    return (
        <div className="min-h-screen bg-[#f5f0ff]">
            <FlashMessage />

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
                            <p className="text-sm font-black">
                                {auth.user?.name}
                            </p>

                            <p className="text-xs font-bold text-neutral-300">
                                {auth.user?.email}
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
                        onClick={() => setOpen((value) => !value)}
                        className="grid h-11 w-11 place-items-center border-2 border-white bg-[#ffd93d] text-xl font-black text-black lg:hidden"
                        aria-label="Buka navigasi admin"
                    >
                        {open ? "×" : "☰"}
                    </button>
                </div>
            </header>

            <div className="mx-auto grid max-w-[1600px] lg:grid-cols-[260px_minmax(0,1fr)]">
                <aside
                    className={`${
                        open ? "block" : "hidden"
                    } border-b-[3px] border-black bg-[#b7a4ff] p-4 lg:block lg:min-h-[calc(100vh-76px)] lg:border-b-0 lg:border-r-[3px]`}
                >
                    <nav className="grid gap-3">
                        {navigation.map((item) => {
                            const active = Boolean(
                                route().current(item.pattern),
                            );

                            return (
                                <Link
                                    key={item.routeName}
                                    href={route(item.routeName)}
                                    onClick={() => setOpen(false)}
                                    className={`border-[3px] border-black px-4 py-3 font-black shadow-[4px_4px_0_#111] ${
                                        active
                                            ? "bg-[#ffd93d]"
                                            : "bg-white hover:bg-[#fff1a8]"
                                    }`}
                                >
                                    {item.label}
                                </Link>
                            );
                        })}

                        <Link
                            href={route("dashboard")}
                            className="border-[3px] border-black bg-[#9ed8ff] px-4 py-3 text-center font-black shadow-[4px_4px_0_#111] lg:hidden"
                        >
                            Lihat Situs
                        </Link>

                        <Link
                            href={route("logout")}
                            method="post"
                            as="button"
                            className="border-[3px] border-black bg-[#ff9c9c] px-4 py-3 text-center font-black shadow-[4px_4px_0_#111] lg:hidden"
                        >
                            Keluar
                        </Link>
                    </nav>
                </aside>

                <main className="min-w-0 p-4 sm:p-6 lg:p-8">
                    <div className="mb-8 border-[3px] border-black bg-white p-5 shadow-[6px_6px_0_#111]">
                        <p className="text-xs font-black uppercase tracking-[0.18em] text-neutral-600">
                            Administrasi BugHunt
                        </p>

                        <h1 className="mt-2 text-3xl font-black tracking-[-0.05em] sm:text-4xl">
                            {title}
                        </h1>

                        {description && (
                            <p className="mt-3 max-w-3xl font-semibold leading-7 text-neutral-700">
                                {description}
                            </p>
                        )}
                    </div>

                    {children}
                </main>
            </div>
        </div>
    );
}
