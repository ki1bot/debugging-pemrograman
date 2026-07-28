import { Link } from "@inertiajs/react";
import { Bug } from "lucide-react";

type ApplicationLogoProps = {
    className?: string;
    href?: string;
};

export default function ApplicationLogo({
    className = "",
    href = "/",
}: ApplicationLogoProps) {
    return (
        <Link
            href={href}
            className={`group inline-flex items-center gap-3 ${className}`}
        >
            <span className="neo-icon-box relative h-10 w-10 overflow-hidden bg-gradient-to-br from-[#ffc84a] via-[#ff9b67] to-[#f56eb3] transition-transform duration-200 group-hover:-rotate-3 group-hover:scale-105">
                <Bug className="h-5 w-5" strokeWidth={2.8} />
            </span>

            <span className="text-xl font-black tracking-[-0.055em] text-[#21162f]">
                Bug
                <span className="neo-gradient-text">Hunt</span>
            </span>
        </Link>
    );
}
