import { ChallengeCard as ChallengeCardType } from "@/types";
import { Link } from "@inertiajs/react";

type ChallengeCardProps = {
    challenge: ChallengeCardType;
};

const categoryBackground: Record<string, string> = {
    javascript: "bg-[#ffd93d]",
    php: "bg-[#b7a4ff]",
    sql: "bg-[#78d9ff]",
};

const difficultyBackground: Record<string, string> = {
    mudah: "bg-[#9ef0b8]",
    menengah: "bg-[#ffbd70]",
    sulit: "bg-[#ff8fa3]",
};

export default function ChallengeCard({ challenge }: ChallengeCardProps) {
    return (
        <article className="nb-card flex h-full flex-col bg-white p-5">
            <div className="mb-5 flex flex-wrap items-center justify-between gap-3">
                <span
                    className={`nb-badge ${
                        categoryBackground[challenge.category.slug] ??
                        "bg-white"
                    }`}
                >
                    {challenge.category.name}
                </span>

                <span
                    className={`nb-badge ${
                        difficultyBackground[challenge.difficulty.slug] ??
                        "bg-white"
                    }`}
                >
                    {challenge.difficulty.name}
                </span>
            </div>

            <h3 className="text-xl font-black leading-tight">
                {challenge.title}
            </h3>

            <p className="mt-3 flex-1 text-sm font-semibold leading-6 text-neutral-700">
                {challenge.description}
            </p>

            {challenge.progress && (
                <div className="mt-5 border-2 border-black bg-[#fff7c7] p-3">
                    <div className="flex items-center justify-between gap-3 text-xs font-black uppercase tracking-wide">
                        <span>
                            {challenge.progress.is_completed
                                ? "Selesai"
                                : "Sedang dikerjakan"}
                        </span>

                        <span>{challenge.progress.best_score} poin</span>
                    </div>

                    <div className="mt-2 h-3 border-2 border-black bg-white">
                        <div
                            className="h-full bg-[#ff6b6b]"
                            style={{
                                width: `${Math.min(
                                    100,
                                    Math.round(
                                        (challenge.progress.best_score /
                                            challenge.base_points) *
                                            100,
                                    ),
                                )}%`,
                            }}
                        />
                    </div>
                </div>
            )}

            <div className="mt-5 flex items-center justify-between gap-4">
                <strong className="text-sm font-black">
                    Maks. {challenge.base_points} poin
                </strong>

                <Link
                    href={route("challenges.show", challenge.slug)}
                    className="nb-button bg-[#ff6b6b] text-sm"
                >
                    Buru Bug
                </Link>
            </div>
        </article>
    );
}
