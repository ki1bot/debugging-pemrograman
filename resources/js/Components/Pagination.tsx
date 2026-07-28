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

function isPageNumber(label: string): boolean {
    return /^\d+$/.test(label.trim());
}

export default function Pagination({ links }: PaginationProps) {
    if (links.length <= 3) {
        return null;
    }

    return (
        <nav className="nb-pagination mt-8" aria-label="Pagination">
            {links.map((link, index) => {
                const label = normalizeLabel(link.label);
                const className = `nb-page-link ${
                    link.active ? "nb-page-link-active" : ""
                } ${link.url ? "" : "nb-page-link-disabled"}`;

                return link.url ? (
                    <Link
                        key={`${link.label}-${index}`}
                        href={link.url}
                        preserveScroll
                        className={className}
                        aria-current={link.active ? "page" : undefined}
                        data-page-number={isPageNumber(label)}
                    >
                        {label}
                    </Link>
                ) : (
                    <span
                        key={`${link.label}-${index}`}
                        className={className}
                        aria-disabled="true"
                        data-page-number={isPageNumber(label)}
                    >
                        {label}
                    </span>
                );
            })}
        </nav>
    );
}
