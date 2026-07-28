import ApplicationLogo from "@/Components/ApplicationLogo";
import FlashMessage from "@/Components/FlashMessage";
import { PageProps } from "@/types";
import { Link, usePage } from "@inertiajs/react";
import { PropsWithChildren, useEffect, useState } from "react";

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
        label: "Statistik",
        routeName: "admin.statistics.index",
        pattern: "admin.statistics.*",
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
    const page = usePage<PageProps>();
    const { auth } = page.props;
    const [open, setOpen] = useState(false);

    useEffect(() => {
        setOpen(false);
    }, [page.url]);

    useEffect(() => {
        if (!open) {
            return;
        }

        const handleEscape = (event: KeyboardEvent) => {
            if (event.key === "Escape") {
                setOpen(false);
            }
        };

        document.addEventListener("keydown", handleEscape);

        return () => document.removeEventListener("keydown", handleEscape);
    }, [open]);

    return (
        <div className="nb-admin-shell">
            <FlashMessage />

            <header className="nb-admin-header">
                <div className="flex min-h-[76px] items-center justify-between gap-4 px-3 sm:px-6">
                    <div className="flex min-w-0 items-center gap-3 sm:gap-4">
                        <ApplicationLogo
                            href={route("admin.dashboard")}
                            className="[&_.nb-brand-word]:border-white [&_.nb-brand-word]:shadow-[3px_3px_0_#ffffff]"
                        />

                        <span className="hidden rounded-md border-2 border-white bg-[#c3b4ff] px-3 py-1 text-sm font-black text-black md:inline-flex">
                            ADMIN PANEL
                        </span>
                    </div>

                    <div className="hidden items-center gap-3 lg:flex">
                        <div className="max-w-64 text-right">
                            <p className="truncate text-sm font-black">
                                {auth.user?.name}
                            </p>

                            <p className="truncate text-xs font-bold text-neutral-300">
                                {auth.user?.email}
                            </p>
                        </div>

                        <Link
                            href={route("dashboard")}
                            className="nb-button nb-button-sm border-white bg-white text-black shadow-[3px_3px_0_#ffffff]"
                        >
                            Lihat Situs
                        </Link>

                        <Link
                            href={route("logout")}
                            method="post"
                            as="button"
                            className="nb-button nb-button-sm border-white bg-[#ff7468] text-black shadow-[3px_3px_0_#ffffff]"
                        >
                            Keluar
                        </Link>
                    </div>

                    <button
                        type="button"
                        onClick={() => setOpen((value) => !value)}
                        className="grid h-11 w-11 shrink-0 place-items-center rounded-md border-2 border-white bg-[#f8dc4d] text-xl font-black text-black shadow-[2px_2px_0_#ffffff] lg:hidden"
                        aria-label={
                            open
                                ? "Tutup navigasi admin"
                                : "Buka navigasi admin"
                        }
                        aria-expanded={open}
                        aria-controls="admin-navigation"
                    >
                        {open ? (
                            <svg
                                viewBox="0 0 24 24"
                                className="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                strokeWidth="2.5"
                                strokeLinecap="round"
                                aria-hidden="true"
                            >
                                <path d="M6 6l12 12M18 6 6 18" />
                            </svg>
                        ) : (
                            <svg
                                viewBox="0 0 24 24"
                                className="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                strokeWidth="2.5"
                                strokeLinecap="round"
                                aria-hidden="true"
                            >
                                <path d="M4 7h16M4 12h16M4 17h16" />
                            </svg>
                        )}
                    </button>
                </div>
            </header>

            <div className="nb-admin-grid grid lg:grid-cols-[270px_minmax(0,1fr)]">
                <aside
                    id="admin-navigation"
                    className={`${
                        open ? "block" : "hidden"
                    } nb-admin-sidebar border-b-[3px] p-3 sm:p-4 lg:block lg:min-h-[calc(100vh-76px)] lg:border-b-0 lg:border-r-[3px]`}
                >
                    <nav className="grid gap-3" aria-label="Navigasi admin">
                        {navigation.map((item) => {
                            const active = Boolean(
                                route().current(item.pattern),
                            );

                            return (
                                <Link
                                    key={item.routeName}
                                    href={route(item.routeName)}
                                    className={`nb-admin-link ${
                                        active ? "nb-admin-link-active" : ""
                                    }`}
                                    aria-current={active ? "page" : undefined}
                                >
                                    <span>{item.label}</span>
                                    <span aria-hidden="true">→</span>
                                </Link>
                            );
                        })}

                        <div className="mt-1 grid gap-3 lg:hidden">
                            <Link
                                href={route("dashboard")}
                                className="nb-admin-link bg-[#8ed8ff]"
                            >
                                <span>Lihat Situs</span>
                                <span aria-hidden="true">→</span>
                            </Link>

                            <Link
                                href={route("logout")}
                                method="post"
                                as="button"
                                className="nb-admin-link bg-[#ff9eb5] text-left"
                            >
                                <span>Keluar</span>
                                <span aria-hidden="true">→</span>
                            </Link>
                        </div>
                    </nav>
                </aside>

                <main className="min-w-0 p-3 sm:p-6 lg:p-8">
                    <div className="nb-card mb-7 bg-white p-5 sm:mb-8 sm:p-7">
                        <p className="text-xs font-black uppercase tracking-[0.18em] text-neutral-600">
                            Administrasi BugHunt
                        </p>

                        <h1 className="mt-2 break-words text-3xl font-black tracking-[-0.05em] sm:text-4xl lg:text-5xl">
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
