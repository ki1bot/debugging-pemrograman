import { Link } from "@inertiajs/react";
import { Bug } from "lucide-react";

type ApplicationLogoProps = {
    className?: string;
    href?: string;
    textClassName?: string;
};

export default function ApplicationLogo({
    className = "",
    href = "/",
    textClassName = "text-[#21162f]",
}: ApplicationLogoProps) {
    return (
        <Link
            href={href}
            className={`group flex min-w-0 items-center gap-2 sm:inline-flex sm:gap-3 ${className}`}
        >
            <span className="neo-icon-box relative h-9 w-9 shrink-0 overflow-hidden bg-gradient-to-br from-[#ffc84a] via-[#ff9b67] to-[#f56eb3] transition-transform duration-200 group-hover:-rotate-3 group-hover:scale-105 sm:h-10 sm:w-10">
                <Bug className="h-4.5 w-4.5 sm:h-5 sm:w-5" strokeWidth={2.8} />
            </span>

            <span
                className={`min-w-0 whitespace-nowrap text-[0.82rem] font-black leading-none tracking-[-0.045em] min-[360px]:text-sm sm:text-xl sm:tracking-[-0.055em] ${textClassName}`}
            >
                Debugging <span className="neo-gradient-text">Pemrograman</span>
            </span>
        </Link>
    );
}
