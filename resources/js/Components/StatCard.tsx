type StatCardProps = {
    label: string;
    value: string | number;
    description?: string;
    background?: string;
};

export default function StatCard({
    label,
    value,
    description,
    background = "bg-white",
}: StatCardProps) {
    return (
        <article
            className={`nb-card nb-card-interactive relative overflow-hidden p-5 sm:p-6 ${background}`}
        >
            <div
                className="absolute -right-7 -top-7 h-20 w-20 rotate-12 rounded-xl border-[3px] border-black/20 bg-white/35"
                aria-hidden="true"
            />

            <p className="relative text-xs font-black uppercase tracking-[0.14em]">
                {label}
            </p>

            <p className="relative mt-3 break-words text-4xl font-black tracking-[-0.06em] sm:text-5xl">
                {value}
            </p>

            {description && (
                <p className="relative mt-3 text-sm font-bold leading-6 text-neutral-700">
                    {description}
                </p>
            )}
        </article>
    );
}
