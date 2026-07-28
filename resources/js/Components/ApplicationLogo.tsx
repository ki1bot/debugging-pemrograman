import { Link } from "@inertiajs/react";

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
            className={`nb-brand ${className}`}
            aria-label="BugHunt"
        >
            <span className="nb-brand-word bg-[#ff7468]">BUG</span>
            <span className="nb-brand-word bg-[#f8dc4d]">HUNT</span>
        </Link>
    );
}
