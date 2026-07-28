import { PaginationLink } from "@/types";
import { Link } from "@inertiajs/react";

type PaginationProps = {
    links: PaginationLink[];
};

function normalizeLabel(label: string): string {
    return label
        .replace("&laquo; Previous", "Sebelumnya")
        .replace("Next &raquo;", "Berikutnya")
        .replace(/&laquo;/g, "«")
        .replace(/&raquo;/g, "»");
}

export default function Pagination({ links }: PaginationProps) {
    if (links.length <= 3) {
        return null;
    }

    return (
        <nav
            className="mt-8 flex flex-wrap justify-center gap-2"
            aria-label="Pagination"
        >
            {links.map((link, index) => {
                const className = `grid min-h-10 min-w-10 place-items-center border-2 border-black px-3 text-sm font-black shadow-[3px_3px_0_#111] ${
                    link.active ? "bg-[#ffd93d]" : "bg-white"
                } ${
                    link.url
                        ? "hover:bg-[#fff1a8]"
                        : "cursor-not-allowed opacity-40"
                }`;

                return link.url ? (
                    <Link
                        key={`${link.label}-${index}`}
                        href={link.url}
                        preserveScroll
                        className={className}
                    >
                        {normalizeLabel(link.label)}
                    </Link>
                ) : (
                    <span key={`${link.label}-${index}`} className={className}>
                        {normalizeLabel(link.label)}
                    </span>
                );
            })}
        </nav>
    );
}
