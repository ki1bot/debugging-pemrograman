type EmptyStateProps = {
    title: string;
    description: string;
};

export default function EmptyState({ title, description }: EmptyStateProps) {
    return (
        <div className="nb-card bg-[#fff1a8] p-8 text-center">
            <div className="mx-auto grid h-16 w-16 place-items-center border-[3px] border-black bg-white text-3xl font-black shadow-[4px_4px_0_#111]">
                ?
            </div>

            <h3 className="mt-5 text-2xl font-black">{title}</h3>

            <p className="mx-auto mt-3 max-w-xl font-semibold leading-7 text-neutral-700">
                {description}
            </p>
        </div>
    );
}
