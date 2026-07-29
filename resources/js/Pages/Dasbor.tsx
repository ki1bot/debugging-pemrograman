import ChallengeCard from "@/Components/KartuTantangan";
import EmptyState from "@/Components/KeadaanKosong";
import StatCard from "@/Components/KartuStatistik";
import StatusBadge from "@/Components/LencanaStatus";
import AuthenticatedLayout from "@/Layouts/TataLetakTerautentikasi";
import { ChallengeCard as ChallengeCardType, ProgressCard } from "@/types";
import { Head, Link } from "@inertiajs/react";

type DashboardProps = {
    summary: {
        totalPoints: number;
        completedChallenges: number;
        totalChallenges: number;
        totalAttempts: number;
    };
    recentProgress: ProgressCard[];
    recommendedChallenges: ChallengeCardType[];
};

export default function Dashboard({
    summary,
    recentProgress,
    recommendedChallenges,
}: DashboardProps) {
    const completionPercentage =
        summary.totalChallenges === 0
            ? 0
            : Math.round(
                  (summary.completedChallenges / summary.totalChallenges) * 100,
              );

    return (
        <AuthenticatedLayout
            header={
                <div>
                    <p className="text-sm font-black uppercase tracking-[0.18em]">
                        Ringkasan Latihan
                    </p>

                    <h1 className="mt-2 text-4xl font-black tracking-[-0.05em]">
                        Perkembangan latihanmu
                    </h1>
                </div>
            }
        >
            <Head title="Ringkasan Latihan" />

            <div className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <section className="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    <StatCard
                        label="Total Poin"
                        value={summary.totalPoints}
                        description="Diambil dari skor terbaik setiap tantangan"
                        background="bg-[#ffd93d]"
                    />

                    <StatCard
                        label="Tantangan Selesai"
                        value={summary.completedChallenges}
                        description={`Dari ${summary.totalChallenges} tantangan yang tersedia`}
                        background="bg-[#9ef0b8]"
                    />

                    <StatCard
                        label="Jawaban Dikirim"
                        value={summary.totalAttempts}
                        description="Jumlah seluruh percobaan yang pernah kamu kirim"
                        background="bg-[#9ed8ff]"
                    />

                    <StatCard
                        label="Progres"
                        value={`${completionPercentage}%`}
                        description="Persentase tantangan yang sudah kamu selesaikan"
                        background="bg-[#ff9c9c]"
                    />
                </section>

                <section className="mt-12">
                    <div className="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                        <div>
                            <p className="text-sm font-black uppercase tracking-[0.16em]">
                                Terakhir Dikerjakan
                            </p>

                            <h2 className="section-title mt-3">
                                Lanjutkan dari tempat terakhir.
                            </h2>
                        </div>

                        <Link
                            href={route("history.index")}
                            className="nb-button self-start bg-white"
                        >
                            Lihat Riwayat
                        </Link>
                    </div>

                    {recentProgress.length > 0 ? (
                        <div className="mt-8 grid gap-5">
                            {recentProgress.map((progress) => (
                                <article
                                    key={progress.id}
                                    className="nb-card grid gap-5 bg-white p-5 md:grid-cols-[minmax(0,1fr)_auto] md:items-center"
                                >
                                    <div>
                                        <div className="flex flex-wrap items-center gap-3">
                                            <span className="nb-badge bg-[#ffd93d]">
                                                {
                                                    progress.challenge.category
                                                        .name
                                                }
                                            </span>

                                            {progress.latest_status && (
                                                <StatusBadge
                                                    status={
                                                        progress.latest_status
                                                    }
                                                />
                                            )}
                                        </div>

                                        <h3 className="mt-4 text-xl font-black">
                                            {progress.challenge.title}
                                        </h3>

                                        <div className="mt-4 flex flex-wrap gap-x-6 gap-y-2 text-sm font-bold text-neutral-700">
                                            <span>
                                                Skor terbaik:{" "}
                                                {progress.best_score}
                                            </span>

                                            <span>
                                                Percobaan:{" "}
                                                {progress.attempts_count}
                                            </span>

                                            <span>
                                                Petunjuk dibuka:{" "}
                                                {progress.hints_used}
                                            </span>
                                        </div>
                                    </div>

                                    <div className="flex flex-wrap gap-3">
                                        <Link
                                            href={route(
                                                "challenges.show",
                                                progress.challenge.slug,
                                            )}
                                            className="nb-button bg-[#9ed8ff] text-sm"
                                        >
                                            {progress.is_completed
                                                ? "Kerjakan Lagi"
                                                : "Lanjutkan"}
                                        </Link>

                                        {progress.submission_id && (
                                            <Link
                                                href={route(
                                                    "submissions.show",
                                                    progress.submission_id,
                                                )}
                                                className="nb-button bg-[#ffd93d] text-sm"
                                            >
                                                Lihat Hasil Terbaik
                                            </Link>
                                        )}
                                    </div>
                                </article>
                            ))}
                        </div>
                    ) : (
                        <div className="mt-8">
                            <EmptyState
                                title="Belum ada latihan yang dikerjakan"
                                description="Pilih satu tantangan untuk memulai latihan debugging pertamamu."
                            />
                        </div>
                    )}
                </section>

                <section className="mt-14">
                    <div>
                        <p className="text-sm font-black uppercase tracking-[0.16em]">
                            Coba Berikutnya
                        </p>

                        <h2 className="section-title mt-3">
                            Pilih latihan berikutnya.
                        </h2>
                    </div>

                    {recommendedChallenges.length > 0 ? (
                        <div className="mt-8 grid gap-7 md:grid-cols-2 xl:grid-cols-3">
                            {recommendedChallenges.map((challenge) => (
                                <ChallengeCard
                                    key={challenge.id}
                                    challenge={challenge}
                                />
                            ))}
                        </div>
                    ) : (
                        <div className="mt-8">
                            <EmptyState
                                title="Semua tantangan sudah selesai"
                                description="Kamu sudah menyelesaikan seluruh tantangan yang tersedia saat ini."
                            />
                        </div>
                    )}
                </section>
            </div>
        </AuthenticatedLayout>
    );
}
