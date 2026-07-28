import { publicNavigation } from "@/Components/Layout/navigation";
import { Link } from "@inertiajs/react";

export default function PublicFooter() {
    return (
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
                    {publicNavigation.map((item) => (
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
    );
}
