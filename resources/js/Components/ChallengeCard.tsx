import { ChallengeCard as ChallengeCardType } from "@/types";
import { Link } from "@inertiajs/react";

type ChallengeCardProps = {
    challenge: ChallengeCardType;
};

const categoryBackground: Record<string, string> = {
    javascript: "bg-[#f8dc4d]",
    php: "bg-[#c3b4ff]",
    sql: "bg-[#8ed8ff]",
};

const difficultyBackground: Record<string, string> = {
    mudah: "bg-[#9ce6b8]",
    menengah: "bg-[#ffbd70]",
    sulit: "bg-[#ff9eb5]",
};

export default function ChallengeCard({ challenge }: ChallengeCardProps) {
    const progressPercentage =
        challenge.progress && challenge.base_points > 0
            ? Math.min(
                  100,
                  Math.round(
                      (challenge.progress.best_score / challenge.base_points) *
                          100,
                  ),
              )
            : 0;

    return (
        <article className="nb-card nb-card-interactive flex h-full flex-col overflow-hidden bg-white p-5 sm:p-6">
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

            <h3 className="text-xl font-black leading-tight tracking-[-0.025em] sm:text-2xl">
                {challenge.title}
            </h3>

            <p className="mt-3 flex-1 text-sm font-semibold leading-6 text-neutral-700 sm:text-base sm:leading-7">
                {challenge.description}
            </p>

            {challenge.progress && (
                <div className="mt-5 rounded-lg border-2 border-black bg-[#fff4b8] p-3.5">
                    <div className="flex flex-wrap items-center justify-between gap-2 text-xs font-black uppercase tracking-wide">
                        <span>
                            {challenge.progress.is_completed
                                ? "Selesai"
                                : "Sedang dikerjakan"}
                        </span>

                        <span>{challenge.progress.best_score} poin</span>
                    </div>

                    <div
                        className="mt-3 h-3 overflow-hidden rounded-full border-2 border-black bg-white"
                        role="progressbar"
                        aria-label={`Progres ${challenge.title}`}
                        aria-valuemin={0}
                        aria-valuemax={100}
                        aria-valuenow={progressPercentage}
                    >
                        <div
                            className="h-full bg-[#ff7468]"
                            style={{ width: `${progressPercentage}%` }}
                        />
                    </div>
                </div>
            )}

            <div className="mt-5 flex flex-col gap-4 border-t-2 border-black/15 pt-5 sm:flex-row sm:items-center sm:justify-between">
                <strong className="text-sm font-black">
                    Maks. {challenge.base_points} poin
                </strong>

                <Link
                    href={route("challenges.show", challenge.slug)}
                    className="nb-button bg-[#ff7468] text-sm sm:self-auto"
                >
                    Buru Bug
                </Link>
            </div>
        </article>
    );
}
