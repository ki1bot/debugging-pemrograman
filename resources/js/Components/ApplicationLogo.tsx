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
            className={`inline-flex items-center gap-1 font-black tracking-[-0.06em] ${className}`}
        >
            <span className="border-[3px] border-black bg-[#ff6b6b] px-2 py-1 shadow-[3px_3px_0_#111]">
                BUG
            </span>
            <span className="border-[3px] border-black bg-[#ffd93d] px-2 py-1 shadow-[3px_3px_0_#111]">
                HUNT
            </span>
        </Link>
    );
}
