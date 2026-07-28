import ApplicationLogo from "@/Components/ApplicationLogo";
import { authenticatedNavigation } from "@/Components/Layout/navigation";
import { PageProps } from "@/types";
import { Link, usePage } from "@inertiajs/react";
import { useState } from "react";

export default function AuthenticatedHeader() {
    const { auth } = usePage<PageProps>().props;
    const user = auth.user;
    const [open, setOpen] = useState(false);

    return (
        <header className="sticky top-0 z-50 border-b-[3px] border-black bg-[#fff7e6]">
            <div className="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <ApplicationLogo />

                <nav className="hidden items-center gap-2 xl:flex">
                    {authenticatedNavigation.map((item) => (
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

                    {user?.role === "admin" && (
                        <Link
                            href={route("admin.dashboard")}
                            className="border-2 border-black bg-[#b7a4ff] px-3 py-2 text-sm font-black shadow-[2px_2px_0_#111]"
                        >
                            Admin
                        </Link>
                    )}
                </nav>

                <div className="hidden items-center gap-3 xl:flex">
                    <div className="border-2 border-black bg-white px-3 py-2 shadow-[2px_2px_0_#111]">
                        <p className="text-xs font-black uppercase tracking-wide">
                            {user?.name}
                        </p>

                        <p className="text-xs font-bold text-neutral-600">
                            {user?.total_points ?? 0} poin
                        </p>
                    </div>

                    <Link
                        href={route("profile.edit")}
                        className="nb-button bg-[#9ed8ff] text-sm"
                    >
                        Profil
                    </Link>

                    <Link
                        href={route("logout")}
                        method="post"
                        as="button"
                        className="nb-button bg-[#ff9c9c] text-sm"
                    >
                        Keluar
                    </Link>
                </div>

                <button
                    type="button"
                    onClick={() => setOpen((value) => !value)}
                    className="grid h-11 w-11 place-items-center border-[3px] border-black bg-[#ffd93d] text-xl font-black shadow-[3px_3px_0_#111] xl:hidden"
                    aria-label="Buka navigasi"
                >
                    {open ? "×" : "☰"}
                </button>
            </div>

            {open && (
                <div className="border-t-[3px] border-black bg-white px-4 py-4 xl:hidden">
                    <div className="mx-auto grid max-w-7xl gap-2">
                        <div className="border-2 border-black bg-[#fff1a8] p-3">
                            <p className="font-black">{user?.name}</p>

                            <p className="text-sm font-bold">
                                {user?.email} · {user?.total_points ?? 0} poin
                            </p>
                        </div>

                        {authenticatedNavigation.map((item) => (
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

                        {user?.role === "admin" && (
                            <Link
                                href={route("admin.dashboard")}
                                className="border-2 border-black bg-[#b7a4ff] px-4 py-3 font-black"
                            >
                                Dashboard Admin
                            </Link>
                        )}

                        <div className="grid grid-cols-2 gap-2">
                            <Link
                                href={route("profile.edit")}
                                className="border-2 border-black bg-[#9ed8ff] px-4 py-3 text-center font-black"
                            >
                                Profil
                            </Link>

                            <Link
                                href={route("logout")}
                                method="post"
                                as="button"
                                className="border-2 border-black bg-[#ff9c9c] px-4 py-3 text-center font-black"
                            >
                                Keluar
                            </Link>
                        </div>
                    </div>
                </div>
            )}
        </header>
    );
}
