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
        <article className={`nb-card p-5 ${background}`}>
            <p className="text-xs font-black uppercase tracking-[0.14em]">
                {label}
            </p>

            <p className="mt-3 text-4xl font-black tracking-[-0.06em]">
                {value}
            </p>

            {description && (
                <p className="mt-3 text-sm font-bold text-neutral-700">
                    {description}
                </p>
            )}
        </article>
    );
}
