import { adminNavigation } from "@/Components/Layout/navigasi";
import { Link } from "@inertiajs/react";

type AdminSidebarProps = {
    open: boolean;
    close: () => void;
};

export default function AdminSidebar({ open, close }: AdminSidebarProps) {
    return (
        <aside
            className={`${
                open ? "block" : "hidden"
            } border-b-[3px] border-black bg-[#b7a4ff] p-4 lg:block lg:min-h-[calc(100vh-76px)] lg:border-b-0 lg:border-r-[3px]`}
        >
            <nav className="grid gap-3">
                {adminNavigation.map((item) => {
                    const active = Boolean(route().current(item.pattern));

                    return (
                        <Link
                            key={item.routeName}
                            href={route(item.routeName)}
                            onClick={close}
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
    );
}
