import type { LucideIcon } from "lucide-react";

type StatCardProps = {
    label: string;
    value: string | number;
    description?: string;
    background?: string;
    icon?: LucideIcon;
};

export default function StatCard({
    label,
    value,
    description,
    background = "bg-white",
    icon: Icon,
}: StatCardProps) {
    return (
        <article
            className={`nb-card group relative overflow-hidden p-6 ${background}`}
        >
            <div
                aria-hidden="true"
                className="absolute -right-10 -top-10 h-28 w-28 rounded-full border-2 border-[#21162f]/10 bg-white/30"
            />

            <div className="relative flex items-start justify-between gap-4">
                <div>
                    <p className="text-xs font-black uppercase tracking-[0.14em] text-[#665f73]">
                        {label}
                    </p>

                    <p className="mt-3 text-4xl font-black tracking-[-0.065em] text-[#21162f] sm:text-5xl">
                        {value}
                    </p>
                </div>

                {Icon && (
                    <span className="neo-icon-box h-12 w-12 shrink-0 bg-white transition-transform duration-200 group-hover:-rotate-3 group-hover:scale-105">
                        <Icon
                            className="h-6 w-6"
                            strokeWidth={2.7}
                            aria-hidden="true"
                        />
                    </span>
                )}
            </div>

            {description && (
                <p className="relative mt-4 text-sm font-bold leading-6 text-[#665f73]">
                    {description}
                </p>
            )}
        </article>
    );
}
