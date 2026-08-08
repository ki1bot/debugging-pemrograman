import {
    ChallengeCard as ChallengeCardType,
    PageProps,
} from "@/types";
import { Link, usePage } from "@inertiajs/react";
import {
    ArrowRight,
    CheckCircle2,
    Code2,
    Gauge,
    Trophy,
} from "lucide-react";
import { memo } from "react";

type ChallengeCardProps = {
    challenge: ChallengeCardType;
};

const categoryBackground: Record<string, string> = {
    javascript: "bg-[#fff2b8]",
    php: "bg-[#e5ddff]",
    sql: "bg-[#d5f0ff]",
};

const difficultyBackground: Record<string, string> = {
    mudah: "bg-[#d4f8e5]",
    menengah: "bg-[#ffe0bd]",
    sulit: "bg-[#ffd2df]",
};

function ChallengeCard({ challenge }: ChallengeCardProps) {
    const { auth } = usePage<PageProps>().props;

    const progressPercentage = challenge.progress
        ? Math.min(
              100,
              Math.round(
                  (challenge.progress.best_score /
                      Math.max(challenge.base_points, 1)) *
                      100,
              ),
          )
        : 0;

    return (
        <article className="nb-card group relative flex h-full min-w-0 flex-col overflow-hidden bg-white p-5 sm:p-6">
            <div
                aria-hidden="true"
                className="absolute -right-12 -top-12 h-32 w-32 rounded-full bg-gradient-to-br from-[#8dd4fa]/30 to-[#9c88f7]/30"
            />

            <div className="relative mb-5 flex flex-wrap items-start justify-between gap-3">
                <span
                    className={`nb-badge ${
                        categoryBackground[challenge.category.slug] ??
                        "bg-white"
                    }`}
                >
                    <Code2
                        className="h-3.5 w-3.5"
                        strokeWidth={2.8}
                    />
                    {challenge.category.name}
                </span>

                <span
                    className={`nb-badge ${
                        difficultyBackground[challenge.difficulty.slug] ??
                        "bg-white"
                    }`}
                >
                    <Gauge
                        className="h-3.5 w-3.5"
                        strokeWidth={2.8}
                    />
                    {challenge.difficulty.name}
                </span>
            </div>

            <h3 className="relative break-words text-xl font-black leading-tight tracking-[-0.035em] text-[#21162f] sm:text-2xl">
                {challenge.title}
            </h3>

            <p className="relative mt-3 flex-1 text-sm font-semibold leading-7 text-[#665f73]">
                {challenge.description}
            </p>

            {challenge.progress && (
                <div className="relative mt-5 rounded-2xl border border-[#21162f]/10 bg-[#f8f6fc] p-4">
                    <div className="flex flex-wrap items-center justify-between gap-2 text-xs font-black uppercase tracking-wide">
                        <span className="inline-flex min-w-0 items-center gap-1.5">
                            <CheckCircle2
                                className="h-4 w-4"
                                strokeWidth={2.8}
                            />
                            {challenge.progress.is_completed
                                ? "Sudah selesai"
                                : "Belum selesai"}
                        </span>

                        <span className="whitespace-nowrap">
                            {challenge.progress.best_score} poin
                        </span>
                    </div>

                    <div className="mt-3 h-3 overflow-hidden rounded-full border-2 border-[#21162f] bg-white">
                        <div
                            className="h-full rounded-full bg-gradient-to-r from-[#ffc84a] via-[#ff9b67] to-[#f56eb3] transition-[width] duration-500"
                            style={{
                                width: `${progressPercentage}%`,
                            }}
                        />
                    </div>
                </div>
            )}

            <div className="relative mt-6 flex flex-col items-stretch gap-4 border-t border-[#21162f]/10 pt-5 sm:flex-row sm:items-center sm:justify-between">
                <strong className="inline-flex items-center justify-center gap-2 text-center text-sm font-black text-[#21162f] sm:justify-start sm:text-left">
                    <Trophy
                        className="h-4 w-4"
                        strokeWidth={2.7}
                    />
                    Hingga {challenge.base_points} poin
                </strong>

                <Link
                    href={route(
                        "challenges.show",
                        challenge.slug,
                    )}
                    prefetch={Boolean(auth.user)}
                    className="nb-button nb-button-primary w-full text-sm sm:w-auto"
                >
                    Buka Tantangan
                    <ArrowRight
                        className="h-4 w-4"
                        strokeWidth={2.8}
                    />
                </Link>
            </div>
        </article>
    );
}

export default memo(ChallengeCard);